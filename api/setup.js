const express = require("express");
const cors = require("cors");

const projectsRoute = require("./routes/projects");
const tasksRoute = require("./routes/tasks");
const requirementsRoute = require("./routes/requirements");
const usersRoute = require("./routes/users");
const invitationsRoute = require("./routes/invitations");

const { apiErrorHandler } = require("./utilities/error");

module.exports = (app) => {
  app.use(express.json());
  app.use(cors());

  // Routes relating to project functions
  app.use("/projects", projectsRoute);
  app.use("/projects/tasks", tasksRoute);
  app.use("/projects/requirements", requirementsRoute);

  // Routes relating to user functions
  app.use("/users", usersRoute);

  // Routes relating to invitiation functions
  app.use("/invitations", invitationsRoute);

  // *** Must be last in the middleware stack ***
  app.use(apiErrorHandler);
};
