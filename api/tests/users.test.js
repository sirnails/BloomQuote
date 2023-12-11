const request = require("supertest");
const app = require("../server");
const { expect } = require("chai");
const fs = require("fs");

const filePath = __dirname + "/test_data.json";
let { correctUsers, incorrectUsers, projects } = JSON.parse(fs.readFileSync(filePath));

const newUser = {
	firstName: "Rodney",
	lastName: "Johnson",
	email: "rodney@gmail.com",
	password: "password642",
	vpassword: "password642",
};

let newUserToken = "";

describe("User Route", () => {
	// Get token for each user before future tests
	before(async () => {
		Object.keys(correctUsers).forEach(async (userName) => {
			const result = await request(app).post("/users/login").send({
				email: correctUsers[userName].email,
				password: correctUsers[userName].password,
			});
			correctUsers[userName].token = result.body.token;
			fs.writeFileSync(filePath, JSON.stringify({ correctUsers, incorrectUsers, projects }));
		});
	});

	describe("/user/login", () => {
		describe("User's with correct details login successfully", () => {
			// Test that each user logs in successfully
			Object.keys(correctUsers).forEach((userName) => {
				it(`Return auth token and user details when logging in (${userName})`, async () => {
					const result = await request(app).post("/users/login").send({
						email: correctUsers[userName].email,
						password: correctUsers[userName].password,
					});

					expect(result.statusCode).to.equal(200);
					expect(result).to.have.property("body");
					expect(result.body).to.have.property("token");
					expect(result.body).to.have.property("user");
				});
			});
		});

		describe("User's with incorrect details fail to login", () => {
			Object.keys(incorrectUsers).forEach((userName) => {
				it(`Return 404 when logging in with incorrect details (${userName})`, async () => {
					const result = await request(app).post("/users/login").send({
						email: incorrectUsers[userName].email,
						password: incorrectUsers[userName].password,
					});

					expect(result.statusCode).to.equal(404);
					expect(result).to.have.property("body");
				});
			});
		});
	});

	describe("/user/projects", () => {
		describe("User's with valid tokens can retrieve their projects", () => {
			Object.keys(correctUsers).forEach((userName) => {
				it(`Return user's projects based on token (${userName})`, async () => {
					const result = await request(app)
						.get("/users/projects")
						.set("auth-token", correctUsers[userName].token);

					expect(result.statusCode).to.equal(200);
					expect(result).to.have.property("body");
					expect(result.body).to.be.an("array");
				});
			});
		});
	});

	describe("/user/invitations", () => {
		describe("User's with valid tokens can retrieve their invitations", () => {
			Object.keys(correctUsers).forEach((userName) => {
				it(`Return user's invitations based on token (${userName})`, async () => {
					const result = await request(app)
						.get("/users/invitations")
						.set("auth-token", correctUsers[userName].token);

					expect(result.statusCode).to.equal(200);
					expect(result).to.have.property("body");
					expect(result.body).to.be.an("array");
				});
			});
		});
	});

	describe("/user/tasks", () => {
		describe("User's with valid tokens can retrieve their assigned tasks", () => {
			Object.keys(correctUsers).forEach((userName) => {
				it(`Return user's assigned tasks based on token (${userName})`, async () => {
					const result = await request(app)
						.get("/users/tasks")
						.set("auth-token", correctUsers[userName].token);

					expect(result.statusCode).to.equal(200);
					expect(result).to.have.property("body");
					expect(result.body).to.be.an("array");
				});
			});
		});
	});

	describe("/user/regsiter", () => {
		describe("A user can register an account", () => {
			it(`Return their token and user details`, async () => {
				const result = await request(app).post("/users/register").send(newUser);
				expect(result.statusCode).to.equal(201);
				expect(result).to.have.property("body");
				expect(result.body).to.have.property("token");
				expect(result.body).to.have.property("user");
				newUserToken = result.body.token;
			});
		});
	});

	describe("/user/remove", () => {
		describe("A user can delete their account", () => {
			it(`Return success message`, async () => {
				const result = await request(app)
					.delete("/users/remove")
					.set("auth-token", newUserToken);
				expect(result.statusCode).to.equal(200);
			});
		});
	});
});
