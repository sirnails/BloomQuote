const Project = require("../models/Project");

const { isLeader } = require("../utilities/auth.js");
const { addRequirementValidation } = require("../utilities/validation.js");
const { ApiError } = require("../utilities/error");

const addRequirement = async (req, res, next) => {
	// Validate
	const { error } = addRequirementValidation(req.body);
	if (error) {
		next(ApiError.badRequest(error.details[0].message));
		return;
	}

	let project;
	try {
		project = await Project.findById(req.params.projectId);
	} catch (error) {
		next(ApiError.recourseNotFound("No project found with that ID"));
		return;
	}

	if (!isLeader(req.userTokenPayload._id, project, next)) return;

	try {
		await Project.updateOne({ _id: project._id }, { $push: { requirements: [req.body] } });
	} catch (error) {
		next(ApiError.internal("Error updating project"));
		return;
	}

	res.status(201).json({ message: "Successfully added requirement to project" });
};

const updateRequirement = async (req, res, next) => {
	const index = req.params.requirementIndex;
	const newRequirement = req.body;

	let project;
	try {
		project = await Project.findById(req.params.projectId);
	} catch (error) {
		next(ApiError.recourseNotFound("No project found with that ID"));
		return;
	}

	if (!isLeader(req.userTokenPayload._id, project, next)) return;

	let requirements = [];
	for (let req of project.requirements)
		if (req.index === index) requirements.push(newRequirement);
		else requirements.push(req);

	try {
		await Project.updateOne({ _id: project._id }, { $set: { requirements: requirements } });
	} catch (error) {
		next(ApiError.internal("Error adding requirement to project"));
		return;
	}
	res.status(201).json({ message: "Successfully deleted requirement" });
};

const removeRequirement = async (req, res, next) => {
	const index = req.params.requirementIndex;

	let project;
	try {
		project = await Project.findById(req.params.projectId);
	} catch (error) {
		next(ApiError.recourseNotFound("No project found with that ID"));
		return;
	}

	if (!isLeader(req.userTokenPayload._id, project, next)) return;

	let requirement;
	for (const req of project.requirements) if (req.index === index) requirement = req;

	let requirements = [];
	for (const req of project.requirements)
		if (req.index !== requirement.index) requirements.push(req);

	for (let i = 0; i < requirements.length; i++) {
		let indexNum = parseInt(requirements[i].index.match(/\d/g));
		if (indexNum > parseInt(requirement.index.match(/\d/g)))
			requirements[i].index = "REQ-" + String(indexNum - 1);
	}

	try {
		await Project.updateOne({ _id: project._id }, { $set: { requirements: requirements } });
	} catch (error) {
		next(ApiError.internal("Error removing requirement from project"));
		return;
	}

	res.status(201).json({ message: "Successfully deleted requirement" });
};

const getProjectRequirements = async (req, res, next) => {
	try {
		const { requirements } = await Project.findById(req.params.projectId);
		res.json({ requirements });
	} catch (error) {
		next(ApiError.recourseNotFound("No project found with that ID"));
		return;
	}
};

module.exports = {
	addRequirement: addRequirement,
	updateRequirement: updateRequirement,
	removeRequirement: removeRequirement,
	getProjectRequirements: getProjectRequirements,
};
