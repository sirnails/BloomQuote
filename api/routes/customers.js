// Returns all the projects belonging to a specific user (based on their token)
router.get("/projects", verifyToken, getProjects);

// Get all invitations for a user
router.get("/invitations", verifyToken, getInvitations);

// Returns all the tasks allocated to a specific user (based on their token)
router.get("/tasks", verifyToken, getTasks);

// Add task to the user's list of followed tasks
router.patch("/followtask/:projectId/:taskId", verifyToken, followTask);

// Remove task from the user's list of followed tasks
router.delete("/unfollowtask/:projectId/:taskId", verifyToken, unfollowTask);

// Get latest comment for each followed task
router.post(
  "/getlatestfollowedtaskcomments",
  verifyToken,
  getLatestFollowedTaskComments
);
