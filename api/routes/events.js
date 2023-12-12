// Router
const express = require("express");
const router = express.Router();

const { getEvents } = require("../controllers/events");

// Get Events
router.get("/getActiveEvents", getEvents);
