const request = require("supertest");
const app = require("../server");
const { expect } = require("chai");
const fs = require("fs");

const filePath = __dirname + "/test_data.json";
let { correctUsers, projects } = JSON.parse(fs.readFileSync(filePath));

describe("Requirement Route", () => {
	describe("/projects/requirements/getall", () => {
		it(`Returns all the requirements for a given project`, async () => {
			const result = await request(app)
				.get(`/projects/requirements/getall/${projects["New Project"].id}`)
				.set("auth-token", correctUsers.David.token);
			expect(result.statusCode).to.equal(200);
			expect(result).to.have.property("body");
			expect(result.body).to.have.property("requirements");
			expect(result.body.requirements).to.be.an("array");
		});
	});
});
