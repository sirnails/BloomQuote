const express = require("express");
const router = express.Router();
const { verifyToken } = require("../utilities/auth.js");
const {
  register,
  login,
  removeUser,
  getUser,
} = require("../controllers/users");

// Prefix: /users

// Create a new user
router.post("/register", register);

// Remove a current user
router.delete("/remove", verifyToken, removeUser);

// Login
router.post("/login", login);

// Get user details with token
router.get("/getuser", verifyToken, getUser);

module.exports = router;
