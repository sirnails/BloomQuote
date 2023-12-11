// Router
const express = require("express");
const router = express.Router();
const { verifyToken } = require("../utilities/auth.js");

const {
	getTask,
	addTask,
	removeTask,
	updateSubTasks,
	updateStatus,
	getSubTasks,
	updateComments,
	getComments,
} = require("../controllers/tasks");

// Prefix: /projects/tasks

// Get the task with the given id
router.get("/gettask/:projectId/:taskId", verifyToken, getTask);

// Add task to project with id
router.patch("/addtask/:projectId", verifyToken, addTask);

// Remove task
router.patch("/removetask/:projectId/:taskId", verifyToken, removeTask);

// Update subtasks (add/edit/remove)
router.patch("/updatesubtasks/:projectId/:taskId", verifyToken, updateSubTasks);

// Update status
router.patch("/updatestatus/:projectId/:taskId", verifyToken, updateStatus);

// Get all the subtasks for a given project
router.get("/subtasks/:projectId/:taskId", verifyToken, getSubTasks);

// Update comments (add/edit/remove)
router.patch("/comments/updatecomments/:projectId/:taskId", verifyToken, updateComments);

// Get all comments for a given task
router.get("/comments/:projectId/:taskId", verifyToken, getComments);

module.exports = router;
