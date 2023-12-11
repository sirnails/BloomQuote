// Router
const express = require("express");
const router = express.Router();

const { verifyToken } = require("../utilities/auth.js");

const {
	createProject,
	getProject,
	getMemberRole,
	deleteProject,
	getProjectInvitation,
	removeMember,
	changeMemberRole,
	leaveProject,
} = require("../controllers/projects");

// Create project
router.post("/add", verifyToken, createProject);

// Returns a specific project along with the user's role
router.get("/getproject/:projectId", verifyToken, getProject);

// Get the user's role, based on their userId and projectId
router.get("/getrole/:projectId", verifyToken, getMemberRole);

// Delete project
router.delete("/delete/:projectId", verifyToken, deleteProject);

// Get all sent invitations for a project
router.get("/invitations/:projectId", verifyToken, getProjectInvitation);

// Remove user from project
router.patch("/removeuser/:projectId/:userId", verifyToken, removeMember);

// Change team member's role
router.patch("/updateuserrole/:projectId/:userId", verifyToken, changeMemberRole);

// Leave project
router.patch("/leave/:projectId", verifyToken, leaveProject);

module.exports = router;
