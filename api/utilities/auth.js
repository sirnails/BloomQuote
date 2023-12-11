const jwt = require("jsonwebtoken");
const User = require("../models/User");
const { ApiError } = require("../utilities/error");

// Checks if the supplied auth token is valid
const verifyToken = async (req, res, next) => {
	const token = req.header("auth-token");
	if (!token) {
		next(ApiError.forbiddenRequest("No token provided"));
		return;
	}
	let verified;
	try {
		verified = jwt.verify(token, process.env.TOKEN_SECRET);
	} catch (error) {
		next(ApiError.forbiddenRequest("Invalid token"));
		return;
	}
	req.userTokenPayload = verified;
	const user = await User.findById(req.userTokenPayload._id);
	if (!user) {
		next(ApiError.recourseNotFound("No user found"));
		return;
	}
	next();
};

// Get the role the user has for the specified project
const getRole = (userId, project, next) => {
	if (!project) {
		next(ApiError.recourseNotFound("No project found with that ID"));
		return;
	}
	for (const projectMember of project.users)
		if (String(userId) === String(projectMember.userId)) return projectMember.role;
};

const isLeader = async (userId, project, next) => {
	if (getRole(userId, project) !== "Team Leader") {
		next(ApiError.forbiddenRequest("Permission denied"));
		return false;
	} else return true;
};

module.exports = {
	verifyToken,
	isLeader,
	getRole,
};
