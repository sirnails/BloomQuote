const mongoose = require("mongoose");

// Add Username to inviter
const InvitationSchema = mongoose.Schema({
	invitee: {
		userId: {
			type: mongoose.SchemaTypes.ObjectId,
			required: true,
		},
		name: {
			type: String,
			maxlength: 255,
			required: true,
		},
		username: {
			type: String,
			maxlength: 255,
			required: true,
		},
	},
	inviter: {
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
	project: {
		title: {
			type: String,
			required: true,
		},
		projectId: {
			type: mongoose.SchemaTypes.ObjectId,
			required: true,
		},
	},
	role: {
		type: String,
		required: true,
	},
});

module.exports = mongoose.model("invitations", InvitationSchema);
