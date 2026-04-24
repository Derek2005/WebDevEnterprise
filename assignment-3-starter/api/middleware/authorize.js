// Generic authorization middleware that accepts a policy function.
// The policy function will receive (user, resource) and must return true/false.

module.exports = function authorize(policy) {
  return (req, res, next) => {
    const user = req.user;
    const resource = req.mail;

    if (policy(user, resource)) {
      return next();
    }

    const err = new Error("User does not have permission to access this resource.");
    err.statusCode = 403;
    err.error = "Forbidden";
    return next(err);
  };
};