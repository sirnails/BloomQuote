const getTasks = async (req, res, next) => {
  // Get the projectIDs for the project(s) the user belongs to
  const { projects: projectIDs } = await getUserByID(
    req.userTokenPayload._id,
    next
  );
  if (!projectIDs) return;

  let assignedTasks = [];
  try {
    // For each project id they are a member of
    for (const { projectId } of projectIDs) {
      const project = await Project.findById(projectId); // Get the full project from the ID
      // Check if user exisits in that project
      for (const task of project.tasks) {
        for (const assignee of task.assignees) {
          if (String(assignee.userId) === String(req.userTokenPayload._id)) {
            assignedTasks.push({
              task: task,
              parentProjectTitle: project.title,
              parentProjectId: project._id,
            });
          }
        }
      }
    }
  } catch (error) {
    next(ApiError.internal("Something went wrong"));
    return;
  }
  res.status(200).json({ tasks: assignedTasks });
};

const getInvitations = async (req, res, next) => {
  const invitations = await getUserInvitations(req.userTokenPayload._id, next);
  if (!invitations) return;

  res.status(200).json({ invitations: invitations });
};

const getProjects = async (req, res, next) => {
  const userId = req.userTokenPayload._id;

  // Get the projectIDs for the project(s) the user belongs to
  const { projects: projectIDs } = await getUserByID(userId, next);
  if (!projectIDs) return;

  // Get the project data from the projectIDs
  let userProjects = await getUserProjects(projectIDs, userId, next);
  // console.log(userProjects[0].project.tasks);
  res.status(200).json({ projects: userProjects });
};
