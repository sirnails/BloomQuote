// Router
const express = require("express");
const router = express.Router();
const { verifyToken } = require("../utilities/auth.js");
const { deleteInvitation, acceptInvitation, addInvitation } = require("../controllers/invitations");

// Prefix: /invitations

// Delete an invitation
router.delete("/delete/:invitationId", verifyToken, deleteInvitation);

// Accept an invitation (adds user to project)
router.post("/accept/:invitationId", verifyToken, acceptInvitation);

// Create new invitation to project
router.post("/addinvitation/:username", verifyToken, addInvitation);

module.exports = router;
