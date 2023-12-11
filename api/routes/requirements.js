const express = require("express");
const router = express.Router();
const { verifyToken } = require("../utilities/auth.js");

const {
	addRequirement,
	updateRequirement,
	removeRequirement,
	getProjectRequirements,
} = require("../controllers/requirements.js");

// Prefix: /projects/requirements

// Add requirement to project with id
router.patch("/addrequirement/:projectId", verifyToken, addRequirement);

// Update requirement
router.patch("/updaterequirement/:projectId/:requirementIndex", verifyToken, updateRequirement);

// Remove requirement from project
router.patch("/removerequirement/:projectId/:requirementIndex", verifyToken, removeRequirement);

// Get all requirements for a project
router.get("/getall/:projectId", verifyToken, getProjectRequirements);

module.exports = router;
