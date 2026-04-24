const jwt = require("jsonwebtoken");

const SECRET = process.env.JWT_SECRET || "CHANGE_ME_BEFORE_SUBMISSION";

module.exports = function authenticateJWT(req, res, next) {
  const authHeader = req.headers.authorization;

  if (!authHeader) {
    const err = new Error("Authorization header is required.");
    err.statusCode = 401;
    err.error = "Unauthorized";
    return next(err);
  }

  const [scheme, token] = authHeader.split(" ");

  if (scheme !== "Bearer" || !token) {
    const err = new Error("Authorization header must be in the format: Bearer <token>.");
    err.statusCode = 401;
    err.error = "Unauthorized";
    return next(err);
  }

  try {
    const decoded = jwt.verify(token, SECRET);
    req.user = decoded;
    next();
  } catch (e) {
    const err = new Error("Invalid or expired token.");
    err.statusCode = 401;
    err.error = "Unauthorized";
    return next(err);
  }
};