## Users Management API – Laravel (JWT Authentication)

This project is a RESTful API built with Laravel that provides a secure users management system with JWT authentication, role-based access control, policies, and rate limiting.

### Features
- JWT authentication (Login, Refresh, Logout)
- Role-based access (Admin/User) using policies
- Simple Admin logic:
  - First registered user becomes **Admin**
  - Admin can assign new admins later
- Request throttling to protect against brute-force attacks
- Clean architecture (Controllers + Services + Repositories)
- Full API documentation
- Organized endpoints and error responses

### Tech Stack
- Laravel 12
- tymon/jwt-auth
- Policies
- Rate Limiting (Throttle)
- MySQL / PostgreSQL

### Setup
1. Clone the repository
2. Install dependencies
3. Set your `.env`
4. Run migrations and seeders
5. Start the server
