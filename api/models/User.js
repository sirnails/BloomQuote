const mongoose = require("mongoose");

const UserSchema = mongoose.Schema({
	firstName: {
		type: String,
		required: true,
		min: 1,
		max: 255,
	},
	lastName: {
		type: String,
		required: true,
		min: 1,
		max: 255,
	},
	email: {
		type: String,
		required: true,
		min: 6,
		max: 255,
	},
	username: {
		type: String,
		required: true,
	},
	password: {
		type: String,
		required: true,
		min: 6,
		max: 1024,
	},
	projects: [
		{
			projectId: { type: mongoose.Schema.Types.ObjectId, required: true },
			_id: false,
		},
	],
	date: {
		type: Date,
		default: Date.now,
	},
	followedTasks: [
		{
			taskId: { type: mongoose.Schema.Types.ObjectId, required: true },
			projectId: { type: mongoose.Schema.Types.ObjectId, required: true },
			_id: false,
		},
	],
});

module.exports = mongoose.model("users", UserSchema);
