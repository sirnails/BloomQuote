const request = require("supertest");
const app = require("../server");
const { expect } = require("chai");
const fs = require("fs");

const filePath = __dirname + "/test_data.json";
let { correctUsers, projects } = JSON.parse(fs.readFileSync(filePath));

const newTask = {
	reporter: { userId: "623f5db6f28edae3f6c7a9c1", name: "David Cottrell" },
	taskKey: "T3",
	title: "New Example Task",
	description: "Task created for integration testing",
	status: "In Development",
	resolution: "Un-Resolved",
	type: "Bug/system issue",
	dateCreated: "17/04/2022",
	dateDue: "",
	assignees: [{ username: correctUsers.John.username }],
};

let newTaskId = "";

describe("Task Route", () => {
	describe("/projects/tasks/gettask", () => {
		it(`Returns the task for a given project`, async () => {
			const result = await request(app)
				.get(
					`/projects/tasks/gettask/${projects["New Project"].id}/${projects["New Project"].tasks["Login Bug"]}`
				)
				.set("auth-token", correctUsers.David.token);
			expect(result.statusCode).to.equal(200);
			expect(result).to.have.property("body");
		});
	});
	describe("/projects/tasks/addtask", () => {
		it(`Returns id of new task`, async () => {
			const result = await request(app)
				.patch(`/projects/tasks/addtask/${projects["New Project"].id}`)
				.set("auth-token", correctUsers.David.token)
				.send(newTask);
			expect(result.statusCode).to.equal(201);
			expect(result).to.have.property("body");
			expect(result.body).to.have.property("newTaskId");
			newTaskId = result.body.newTaskId;
		});
	});
	describe("/projects/tasks/removetask", () => {
		it(`Returns success message`, async () => {
			const result = await request(app)
				.patch(`/projects/tasks/removetask/${projects["New Project"].id}/${newTaskId}`)
				.set("auth-token", correctUsers.David.token)
				.send(newTask);
			expect(result.statusCode).to.equal(200);
		});
	});

	describe("/projects/tasks/updatestatus", () => {
		it(`Returns success message`, async () => {
			const result = await request(app)
				.patch(
					`/projects/tasks/updatestatus/${projects["New Project"].id}/${projects["New Project"].tasks["Login Bug"]}`
				)
				.set("auth-token", correctUsers.David.token)
				.send({ status: "Ready to Merge" });
			expect(result.statusCode).to.equal(200);
			expect(result).to.have.property("body");
			expect(result.body).to.have.property("status");
			expect(result.body.status).to.equal("Ready to Merge");
		});
	});

	describe("/projects/tasks/subtasks", () => {
		it(`Returns subtasks for a given project's task`, async () => {
			const result = await request(app)
				.get(
					`/projects/tasks/subtasks/${projects["New Project"].id}/${projects["New Project"].tasks["Login Bug"]}`
				)
				.set("auth-token", correctUsers.David.token);
			expect(result.statusCode).to.equal(200);
			expect(result).to.have.property("body");
			expect(result.body).to.have.property("subtasks");
		});
	});
	describe("/projects/tasks/comments", () => {
		it(`Returns comments for a given project's task`, async () => {
			const result = await request(app)
				.get(
					`/projects/tasks/comments/${projects["New Project"].id}/${projects["New Project"].tasks["Login Bug"]}`
				)
				.set("auth-token", correctUsers.David.token);
			expect(result.statusCode).to.equal(200);
			expect(result.body.comments).to.be.an("array");
		});
	});
});
