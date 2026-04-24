// Centralized error handler.
// This should be the LAST app.use(...) in server.js.

module.exports = function errorHandler(err, req, res, next) {
  const statusCode = err.statusCode || 500;

  const errorMap = {
    400: "BadRequest",
    401: "Unauthorized",
    403: "Forbidden",
    404: "NotFound",
    429: "TooManyRequests",
    500: "InternalServerError"
  };

  const errorName = err.error || errorMap[statusCode] || "InternalServerError";

  const safeMessage =
    statusCode === 500
      ? "An unexpected error occurred."
      : err.message || "Request failed.";

  console.error("Unhandled error for request", req.requestId, err);

  if (statusCode === 429 && err.retryAfter) {
    res.set("Retry-After", String(err.retryAfter));
  }

  res.status(statusCode).json({
    error: errorName,
    message: safeMessage,
    statusCode,
    requestId: req.requestId || null,
    timestamp: new Date().toISOString()
  });
};