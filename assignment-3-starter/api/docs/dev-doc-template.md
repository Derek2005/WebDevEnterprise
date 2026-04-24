Assignment 3 – Developer Documentation

1. Overview
This API provides authenticated access to mail messages in a simple mail system. It uses JWT-based authentication, role-based access control (RBAC), request logging with request IDs, rate limiting, and centralized error handling. The main use case is to allow users to securely retrieve mail messages while ensuring that regular users can only access their own mail and administrators can access all mail.

2. Authentication
Auth Method:
Scheme: Bearer token (JWT)
Endpoint: POST /auth/login

Request Body:
{
  "username": "user1",
  "password": "user123"
}

Success Response:
{
  "token": "..."
}

Tokens include userId and role and are valid for 1 hour.

Header:
Authorization: Bearer <token>

3. Roles & Access Rules
admin: can view all mail
user: can view only own mail

4. Endpoints
POST /auth/login – returns JWT

GET /mail/:id
- Requires token
- admin: any mail
- user: own mail only

GET /status
- returns {"status":"ok"}

5. Rate Limiting
- Based on IP
- Limited by RATE_LIMIT_MAX per RATE_LIMIT_WINDOW_SECONDS
- Exceeding limit returns 429

6. Error Format
{
  "error": "...",
  "message": "...",
  "statusCode": 400,
  "requestId": "...",
  "timestamp": "..."
}

7. Example Flow
Login → get token → access /mail/2 → success
Access another user’s mail → 403 Forbidden

