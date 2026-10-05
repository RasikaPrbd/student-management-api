# Student Management REST API

A Laravel-based REST API for managing students, courses, authentication, and user roles.

## Features

- User Registration
- User Login and Logout
- Laravel Sanctum Authentication
- Role-Based Authorization
- Admin Middleware
- Student CRUD
- Course CRUD
- Student-Course Relationship
- Student Search
- Pagination
- Request Validation
- API Resources
- Standard API Error Responses
- Automated API Tests

## Technologies

- PHP 8.3
- Laravel 13
- MySQL
- Laravel Sanctum
- PHPUnit
- Postman

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/register` | Register user |
| POST | `/api/login` | Login user |
| POST | `/api/logout` | Logout user |
| GET | `/api/user` | Get logged-in user |

### Students

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/students` | Get all students |
| POST | `/api/students` | Create student |
| GET | `/api/students/{id}` | Get student |
| PUT | `/api/students/{id}` | Update student |
| DELETE | `/api/students/{id}` | Delete student |

### Courses

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/courses` | Get all courses |
| POST | `/api/courses` | Create course |
| GET | `/api/courses/{id}` | Get course |
| GET | `/api/courses/{id}/students` | Get course students |
| PUT | `/api/courses/{id}` | Update course |
| DELETE | `/api/courses/{id}` | Delete course |

## Authentication

Protected API routes use Laravel Sanctum.

Send the token with requests:

```text
Authorization: Bearer YOUR_TOKEN
```

## Roles

The API supports two roles:

- user
- admin

Only admins can delete students and courses.

## Testing

Run all automated tests:

```bash
php artisan test
```

## Installation

Clone the repository:

```bash
git clone https://github.com/RasikaPrbd/student-management-api.git
```

Go to the project:

```bash
cd student-management-api
```

Install dependencies:

```bash
composer install
```

Create `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Configure your MySQL database in `.env`.

Run migrations:

```bash
php artisan migrate
```

Start the server:

```bash
php artisan serve
```

API URL:

```text
http://127.0.0.1:8000/api
```

## Author

Rasika Prabodha

GitHub: https://github.com/RasikaPrbd