const Joi = require("@hapi/joi");

const registerValidation = (data) => {
	const schema = Joi.object({
		firstName: Joi.string().min(1).required(),
		lastName: Joi.string().min(1).required(),
		email: Joi.string().min(6).required().email(),
		password: Joi.string().min(6).required(),
		vpassword: Joi.string().min(6).required(),
		projects: Joi.array(),
		invitations: Joi.array(),
	});
	return schema.validate(data);
};

const loginValidation = (data) => {
	const schema = Joi.object({
		email: Joi.string().min(6).required().email(),
		password: Joi.string().min(6).required(),
	});
	return schema.validate(data);
};

const statusUpdateValidation = (data) => {
	const schema = Joi.object({
		status: Joi.string()
			.valid("In Development", "Testing", "In Review", "Ready to Merge", "Resolved")
			.required(),
	});
	return schema.validate(data);
};

const createInviteValidation = (data) => {
	const schema = Joi.object({
		inviter: Joi.object({
			userId: Joi.string().required(),
			name: Joi.string().required(),
		}).required(),
		project: Joi.object({
			title: Joi.string().required(),
			projectId: Joi.string().required(),
		}).required(),
		role: Joi.string().required(),
	});
	return schema.validate(data);
};

const createProjectValidation = (data) => {
	const schema = Joi.object({
		title: Joi.string().required(),
		users: Joi.array()
			.items({
				userId: Joi.string().required(),
				name: Joi.string().required(),
				username: Joi.string().required(),
				role: Joi.string().required(),
			})
			.max(1)
			.required(),
		tasks: Joi.array().max(1),
		description: Joi.string().allow(null, ""),
		githubURL: Joi.string().allow(null, ""),
	});
	return schema.validate(data);
};

const createTaskValidation = (data) => {
	const schema = Joi.object({
		taskKey: Joi.string().required(),
		title: Joi.string().required(),
		description: Joi.string().allow(null, ""),
		type: Joi.string().required(),
		assignees: Joi.array()
			.items({
				username: Joi.string().required(),
			})
			.min(1)
			.required(),
		reporter: Joi.object({
			userId: Joi.string().required(),
			name: Joi.string().required(),
		}).required(),
		status: Joi.string().required(),
		resolution: Joi.string().required(),
		dateCreated: Joi.string().required(),
		dateDue: Joi.string().allow(null, ""),
	});
	return schema.validate(data);
};

const addRequirementValidation = (data) => {
	const schema = Joi.object({
		type: Joi.string().required(),
		index: Joi.string().required(),
		systemName: Joi.string().required(),
		preconditions: Joi.array().items(Joi.string()),
		systemResponses: Joi.array().items(Joi.string().min(1)),
		fullText: Joi.string().required(),
		feature: Joi.string().allow(null, ""),
		trigger: Joi.string().allow(null, ""),
		unwantedTrigger: Joi.string().allow(null, ""),
		order: Joi.array().items(Joi.string()),
	});
	return schema.validate(data);
};

module.exports = {
	loginValidation: loginValidation,
	createProjectValidation: createProjectValidation,
	createTaskValidation: createTaskValidation,
	registerValidation: registerValidation,
	createInviteValidation: createInviteValidation,
	addRequirementValidation: addRequirementValidation,
	addRequirementValidation: addRequirementValidation,
	statusUpdateValidation: statusUpdateValidation,
};
