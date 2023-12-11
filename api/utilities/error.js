class ApiError {
	constructor(status, message) {
		this.status = status;
		this.message = message;
	}

	static badRequest = (msg) => new ApiError(400, msg);
	static recourseNotFound = (msg) => new ApiError(404, msg);
	static forbiddenRequest = (msg) => new ApiError(403, msg);
	static invalidCredentials = (msg) => new ApiError(401, msg);
	static internal = (msg) => new ApiError(500, msg);
}

const apiErrorHandler = (err, req, res, next) => {
	if (err instanceof ApiError) {
		res.status(err.status).json(err.message);
		return;
	}
	res.status(500).json("something went wrong");
};

module.exports.ApiError = ApiError;
module.exports.apiErrorHandler = apiErrorHandler;
