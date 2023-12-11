// Models
const Project = require("../models/Project");
const User = require("../models/User");
const Invitation = require("../models/Invitation");
const { ApiError } = require("../utilities/error");

const { isLeader, getRole } = require("../utilities/auth.js");
const { createProjectValidation } = require("../utilities/validation.js");
const { getUserProjects, getProjectByID, getUserByID } = require("./common.js");

const createProject = async (req, res, next) => {
	const user = await getUserByID(req.userTokenPayload._id);
	if (!user) return;

	const newProject = {
		title: req.body.title,
		users: [
			{
				userId: String(user._id),
				name: user.firstName + " " + user.lastName,
				username: user.username,
				role: "Team Leader",
			},
		],
		tasks: [],
		description: req.body.description,
		githubURL: req.body.githubURL,
	};

	// Validate
	const { error } = createProjectValidation(newProject);
	if (error) {
		next(ApiError.badRequest(error.details[0].message));
		return;
	}

	// Save new project document
	const project = new Project(newProject);
	await project.save();

	// Save reference to project to the creating user's list of projects
	user.projects.push({ projectId: project._id });
	await user.save();

	// Get updated list of user's projects
	const userProjects = await getUserProjects(user.projects, user.id);
	if (!userProjects) return;

	// Return updated list of projects
	res.status(201).json({ projects: userProjects });
};

const getProject = async (req, res, next) => {
	const project = await getProjectByID(req.params.projectId, next);
	if (!project) return;

	let userExists = false;
	for (const user of project.users) {
		if (req.userTokenPayload._id == user.userId) {
			userExists = true;
			break;
		}
	}

	if (!userExists) {
		next(ApiError.forbiddenRequest("You are not a member of this project"));
		return;
	}

	const role = getRole(req.userTokenPayload._id, project, next);
	if (!role) return;

	res.status(200).json({ project: project, role: role });
};

const getMemberRole = async (req, res, next) => {
	const project = await getProjectByID(req.params.projectId, next);
	if (!project) return;

	const user = await getUserByID(req.userTokenPayload._id);
	if (!user) return;

	let role = getRole(user._id, project, next);
	if (!role) return;

	return res.status(200).json({ role: role });
};

const deleteProject = async (req, res, next) => {
	const project = await getProjectByID(req.params.projectId, next);
	if (project === undefined) return;

	if (!isLeader(req.userTokenPayload._id, project, next)) return;

	try {
		// Remove project from each member's "projects" list
		for (const user of project.users)
			await User.updateOne(
				{ _id: user.userId },
				{ $pull: { projects: { projectId: project._id } } },
				{ safe: true, multi: true }
			);
	} catch (error) {
		next(ApiError.internal("Error removing project from users"));
		return;
	}

	try {
		// Delete project
		await Project.deleteOne({ _id: project._id });
	} catch (error) {
		next(ApiError.internal("Error removing project"));
		return;
	}

	res.status(200).json({ message: "Successfully removed project" });
};

const getProjectInvitation = async (req, res, next) => {
	try {
		const invitations = await Invitation.find({
			"project.projectId": String(req.params.projectId),
		});
		res.status(200).json({ invitations });
		return;
	} catch (error) {
		next(ApiError.recourseNotFound("No invitations found with that ID"));
		return;
	}
};

const removeMember = async (req, res, next) => {
	const project = await getProjectByID(req.params.projectId, next);
	if (!project) return;

	const userToRemove = await getUserByID(req.params.userId);
	if (!userToRemove) return;

	if (!isLeader(req.userTokenPayload._id, project, next)) return;

	try {
		await Project.updateOne(
			{ _id: project._id },
			{ $pull: { users: { userId: userToRemove._id } } },
			{ safe: true, multi: true }
		);
	} catch (error) {
		next(ApiError.internal("Could not remove user from project"));
		return;
	}

	try {
		await User.updateOne(
			{ _id: userToRemove._id },
			{ $pull: { projects: { projectId: project._id } } },
			{ safe: true, multi: true }
		);
	} catch (error) {
		next(ApiError.internal("Could not remove project from user"));
		return;
	}

	res.status(200).json({ message: "Successfully removed user" });
};

const leaveProject = async (req, res, next) => {
	// Get requesting user
	const user = await getUserByID(req.userTokenPayload._id);
	if (!user) return;

	// Find project to be left
	const project = await getProjectByID(req.params.projectId, next);
	if (!project) return;

	// Remove user from project
	try {
		await Project.updateOne(
			{ _id: project._id },
			{ $pull: { users: { userId: user._id } } },
			{ safe: true, multi: true }
		);
	} catch (error) {
		next(ApiError.internal("Could not remove user from project"));
		return;
	}

	// Remove project from user
	try {
		await User.updateOne(
			{ _id: user._id },
			{ $pull: { projects: { projectId: project._id } } },
			{ safe: true, multi: true }
		);
	} catch (error) {
		next(ApiError.internal("Could not remove project from user"));
		return;
	}

	res.status(200).json({ message: "Successfully left project" });
};

const changeMemberRole = async (req, res, next) => {
	const userId = req.params.userId;
	const newRole = req.body.role;

	const project = await getProjectByID(req.params.projectId, next);
	if (!project) return;

	if (!isLeader(req.userTokenPayload._id, project, next)) return;

	let newUsers = [];
	for (let i = 0; i < project.users.length; i++) {
		if (String(project.users[i].userId) === String(userId)) project.users[i].role = newRole;
		newUsers.push(project.users[i]);
	}

	try {
		await Project.updateOne(
			{
				_id: project._id,
			},
			{ $set: { users: newUsers } }
		);
	} catch (error) {
		next(ApiError.internal("Could not update project's users"));
		return;
	}

	res.status(200).json({ message: "Successfully changed member's role" });
};

module.exports = {
	createProject: createProject,
	getProject: getProject,
	getMemberRole: getMemberRole,
	deleteProject: deleteProject,
	getProjectInvitation: getProjectInvitation,
	removeMember: removeMember,
	changeMemberRole: changeMemberRole,
	leaveProject: leaveProject,
};
