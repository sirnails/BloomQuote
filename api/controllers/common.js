const Invitation = require("../models/Invitation");
const Project = require("../models/Project");
const User = require("../models/User");

const { ApiError } = require("../utilities/error");

//Initials followed by 6 digits
const generateUsername = (firstName, lastName) => {
	let initials = firstName[0] + lastName[0];
	let ran = Math.floor(Math.random() * 1000000);
	return initials + ran.toString();
};

const getUserProjects = async (projectIds, userId, next) => {
	try {
		let userProjects = [];
		// For each of the user's project IDs
		for (const { projectId } of projectIds) {
			// Find the project
			const project = await Project.findById(projectId);
			// For each of the user's within the project
			for (const user of project.users) {
				// Get the project and their role
				if (String(user.userId) === String(userId))
					userProjects.push({
						project: project,
						role: user.role,
					});
			}
		}
		return userProjects;
	} catch (error) {
		next(ApiError.internal("Error getting user's projects"));
		return;
	}
};

const getUserByID = async (userId, next) => {
	try {
		const user = await User.findById(userId);
		return user;
	} catch (error) {
		next(ApiError.recourseNotFound("No user found with that ID"));
		return;
	}
};

const getProjectByID = async (projectID, next) => {
	try {
		const project = await Project.findById(projectID);
		return project;
	} catch (error) {
		next(ApiError.recourseNotFound("No project found with that ID"));
		return;
	}
};

const getInvitiationByID = async (invitiationID, next) => {
	try {
		const invitation = await Invitation.findById(invitiationID);
		return invitation;
	} catch (error) {
		next(ApiError.recourseNotFound("No invitation found with that ID"));
		return;
	}
};

const getUserInvitations = async (userID, next) => {
	try {
		const invitations = await Invitation.find({ "invitee.userId": userID });
		return invitations;
	} catch (error) {
		next(ApiError.recourseNotFound("No user found with that ID"));
		return;
	}
};

module.exports = {
	getUserProjects: getUserProjects,
	generateUsername: generateUsername,
	getUserByID: getUserByID,
	getProjectByID: getProjectByID,
	getInvitiationByID: getInvitiationByID,
	getUserInvitations: getUserInvitations,
};
