const mongoose = require("mongoose");

var TaskSchema = mongoose.Schema({
	taskKey: { type: String, required: true },
	title: { type: String, required: true },
	description: { type: String },
	type: { type: String, required: true },
	assignees: [
		{
			userId: {
				type: mongoose.SchemaTypes.ObjectId,
				required: true,
			},
			name: {
				type: String,
				maxlength: 255,
				required: true,
			},
			_id: false,
		},
	],
	reporter: {
		userId: {
			type: mongoose.SchemaTypes.ObjectId,
			required: true,
		},
		name: {
			type: String,
			maxlength: 255,
			required: true,
		},
	},
	status: { type: String, required: true },
	resolution: { type: String, required: true },
	dateCreated: { type: String, required: true },
	dateUpdated: { type: String },
	dateDue: { type: String },
	comments: [
		{
			authorName: { type: String, required: true },
			authorId: { type: mongoose.SchemaTypes.ObjectId, required: true },
			content: { type: String, required: true },
			taggedUsers: [mongoose.Schema.Types.ObjectId],
			dateAdded: { type: String, required: true },
		},
	],
	subtasks: {
		toDo: [{ type: String }],
		inProgress: [{ type: String }],
		complete: [{ type: String }],
	},
});

var userSchema = mongoose.Schema({
	userId: {
		type: mongoose.SchemaTypes.ObjectId,
		required: true,
	},
	name: {
		type: String,
		required: true,
	},
	username: {
		type: String,
		required: true,
	},
	role: {
		type: String,
		enum: ["Team Leader", "Developer", "Client"],
		required: true,
	},
	_id: false,
});

var RequirementSchema = mongoose.Schema({
	type: {
		type: String,
		required: true,
	},
	index: {
		type: String,
		required: true,
	},
	systemName: {
		type: String,
		required: true,
	},
	trigger: {
		type: String,
		required: true,
	},
	preconditions: [{ type: String, required: true }],
	systemResponses: [{ type: String, required: true }],
	fullText: { type: String },
	feature: { type: String },
	unwantedTrigger: { type: String },
	order: [{ type: String }],
});

const ProjectSchema = mongoose.Schema({
	title: {
		type: String,
		required: true,
	},
	users: {
		type: [userSchema],
		validate: (users) => Array.isArray(users) && users.length > 0,
	},
	tasks: [TaskSchema],
	requirements: [RequirementSchema],
	description: {
		type: String,
	},
	githubURL: {
		type: String,
	},
});

module.exports = mongoose.model("projects", ProjectSchema);
