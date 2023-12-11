require("dotenv/config");
const express = require("express");
const mongoose = require("mongoose");

const app = express();

// Setup Express App
require("./setup")(app);

//Connect to DB
mongoose
  .connect(process.env.DB_CONNECTION, {
    useNewUrlParser: true,
    useUnifiedTopology: true,
  })
  .then(() => console.log("Connected to database successfully"))
  .catch((e) => {
    console.log("Error connecting to database: ", e);
  });

app.listen("9000", () => {
  console.log("Server is running on port " + "9000");
});

module.exports = app;
