// Models
const Project = require("../models/Project");
const User = require("../models/User");
const Invitation = require("../models/Invitation");

// Utility functions and errors
const { isLeader } = require("../utilities/auth.js");
const { createInviteValidation } = require("../utilities/validation.js");
const { ApiError } = require("../utilities/error");

const { getUserProjects, getUserInvitations } = require("./common.js");

const deleteInvitation = async (req, res, next) => {
	try {
		await Invitation.deleteOne({ _id: req.params.invitationId });
		res.status(200).json({ message: "Successfully deleted invite" });
	} catch (error) {
		next(ApiError.recourseNotFound("No invitation found with that ID"));
		return;
	}
};

const acceptInvitation = async (req, res, next) => {
	// Get the invitation from the given ID
	let invitation;
	try {
		invitation = await Invitation.findById(req.params.invitationId);
	} catch (error) {
		next(ApiError.recourseNotFound("No invitation found with that ID"));
		return;
	}

	// Find the project with the ID found in the invitation
	let project;
	try {
		project = await Project.findById(invitation.project.projectId);
	} catch (error) {
		next(ApiError.recourseNotFound("No project found with that ID"));
		return;
	}

	// Find the invited user by their ID
	let userToAdd;
	try {
		userToAdd = await User.findById(invitation.invitee.userId);
	} catch (error) {
		next(ApiError.recourseNotFound("No user found with that ID"));
		return;
	}

	// Check the user is not already a member of the project
	for (existingUser of project.users) {
		if (String(userToAdd._id) === String(existingUser.userId)) {
			next(ApiError.badRequest("This user is already a member of this project"));
			return;
		}
	}

	try {
		// Add project to user's list of project
		await User.updateOne(
			{ _id: userToAdd._id },
			{
				$push: {
					projects: [{ projectId: project._id }],
				},
			}
		);
	} catch (error) {
		next(ApiError.internal("Error adding project to user"));
		return;
	}

	try {
		// Add user to the project's list of users
		await Project.updateOne(
			{ _id: project._id },
			{
				$push: {
					users: [
						{
							userId: userToAdd._id,
							name: userToAdd.firstName + " " + userToAdd.lastName,
							username: userToAdd.username,
							role: invitation.role,
						},
					],
				},
			}
		);
	} catch (error) {
		next(ApiError.internal("Error adding user to project"));
		return;
	}

	try {
		// Invitation can now be deleted
		await Invitation.deleteOne({ _id: invitation._id });
	} catch (error) {
		next(ApiError.internal("Error deleting invitation"));
		return;
	}

	// Get their updated list of projects
	const newProjects = await getUserProjects(
		[...userToAdd.projects, { projectId: project._id }],
		userToAdd._id,
		next
	);
	if (!newProjects) {
		next(ApiError.internal("Error deleting invitation"));
		return;
	}

	// Get their updated list of invitations
	// ...
	const newInvitations = await getUserInvitations(req.userTokenPayload._id, next);
	if (!newInvitations) return;

	// Return their list of new invitations and projects
	res.status(201).json({ updatedProjects: newProjects, updatedInvitations: newInvitations });
};

const addInvitation = async (req, res, next) => {
	let invitedUser;
	try {
		invitedUser = await User.findOne({ username: req.params.username }); // Get the full details for the invited user
	} catch (error) {
		next(ApiError.recourseNotFound("No user found with that username"));
		return;
	}

	// Validate invite data
	const { error } = createInviteValidation(req.body);
	if (error) {
		next(ApiError.badRequest(error.details[0].message));
		return;
	}

	let project;
	try {
		project = await Project.findById(req.body.project.projectId);
	} catch (error) {
		next(ApiError.recourseNotFound("No project found with that ID"));
		return;
	}

	// User must be a Team Leader in order to create project invitations
	if (!isLeader(req.userTokenPayload._id, project, next)) return;

	// Check user doesnt already exist in project
	for (const existingUser of project.users) {
		if (String(invitedUser._id) === String(existingUser.userId)) {
			next(ApiError.badRequest("This user is already a member of this project"));
			return;
		}
	}

	const existingInvitations = await Invitation.find({ "invitee.userId": invitedUser._id });

	for (const invitation of existingInvitations) {
		console.log(invitation);
		if (String(invitation.project.projectId) === String(project._id)) {
			next(ApiError.badRequest("This user has already been invited"));
			return;
		}
	}

	// if (await Invitation.findOne({ "invitee.userId": invitedUser._id })) {
	// 	next(ApiError.badRequest("This user has already been invited"));
	// 	return;
	// }

	// Create object for invited user's details
	const invitee = {
		userId: invitedUser._id,
		name: invitedUser.firstName + " " + invitedUser.lastName,
		username: invitedUser.username,
	};

	// Add invitee object to the invitation
	req.body["invitee"] = invitee;

	// Save invitation
	const invitation = new Invitation(req.body);

	await invitation.save();

	res.status(201).json({ message: "Successfully invited user" });
};

module.exports = {
	deleteInvitation: deleteInvitation,
	acceptInvitation: acceptInvitation,
	addInvitation: addInvitation,
};
