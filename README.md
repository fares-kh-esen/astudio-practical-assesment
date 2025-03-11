# Laravel Project Management API

## Overview

This is a Laravel-based Project Management API with authentication, timesheets, and an Entity-Attribute-Value (EAV) system for dynamic project attributes. It includes CRUD operations, filtering, and authentication using Laravel Passport.

---

## Features

-   User authentication (Register, Login, Logout) using Laravel Passport
-   Manage Users, Projects, and Timesheets
-   Entity-Attribute-Value (EAV) system for dynamic project attributes
-   Flexible filtering system supporting both regular and EAV attributes
-   RESTful API with proper validation and error handling

---

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/fares-kh-esen/astudio-practical-assesment.git
cd astudio-practical-assesment
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Update `.env` file with your database and mail settings.

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Set Up Database

```bash
php artisan migrate --seed
```

### 6. Install Laravel Passport

```bash
php artisan passport:install
```

### 7. Start the Server

```bash
php artisan serve
```

---

## API Documentation

### Authentication

#### Register

```http
POST /api/register
```

**Request Body:**

```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login

```http
POST /api/login
```

**Request Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "token": "your-access-token"
}
```

#### Logout

```http
POST /api/logout
```

**Headers:**

```json
Authorization: Bearer your-access-token
```

---

### Users

#### Get All Users

```http
GET /api/users
```

#### Get User by ID

```http
GET /api/users/{id}
```

#### Create User

```http
POST /api/users
```

#### Update User

```http
PUT /api/users/{id}
```

#### Delete User

```http
DELETE /api/users/{id}
```

---

### Projects

#### Get All Projects

```http
GET /api/projects
```

#### Get Project by ID

```http
GET /api/projects/{id}
```

#### Create Project

```http
POST /api/projects
```

#### Update Project

```http
PUT /api/projects/{id}
```

#### Delete Project

```http
DELETE /api/projects/{id}
```

<!-- #### Filter Projects

```http
GET /api/projects?filters[name]=ProjectA&filters[department]=IT
```

--- -->

#### Filter Projects

```http
GET /api/projects
```

**Request Body:**

```json
{
    "filters": {
        "name": { "operator": "LIKE", "value": "a%" },
        "department": { "operator": "=", "value": "IT" }
    }
}
```

**Response:**

```json
[
    {
        "id": 20,
        "name": "At illum.",
        "status": "inactive",
        "created_at": "2025-03-11T13:45:44.000000Z",
        "updated_at": "2025-03-11T13:45:44.000000Z",
        "attribute_values": [
            {
                "id": 77,
                "attribute_id": 1,
                "entity_id": 20,
                "entity_type": "App\\Models\\Project",
                "value": "IT",
                "created_at": "2025-03-11T13:45:44.000000Z",
                "updated_at": "2025-03-11T13:45:44.000000Z",
                "attribute": {
                    "id": 1,
                    "name": "department",
                    "type": "text",
                    "created_at": null,
                    "updated_at": null
                }
            },
            {
                "id": 78,
                "attribute_id": 2,
                "entity_id": 20,
                "entity_type": "App\\Models\\Project",
                "value": "1971-12-21",
                "created_at": "2025-03-11T13:45:44.000000Z",
                "updated_at": "2025-03-11T13:45:44.000000Z",
                "attribute": {
                    "id": 2,
                    "name": "start_date",
                    "type": "date",
                    "created_at": null,
                    "updated_at": null
                }
            },
            {
                "id": 79,
                "attribute_id": 3,
                "entity_id": 20,
                "entity_type": "App\\Models\\Project",
                "value": "1974-06-18",
                "created_at": "2025-03-11T13:45:44.000000Z",
                "updated_at": "2025-03-11T13:45:44.000000Z",
                "attribute": {
                    "id": 3,
                    "name": "end_date",
                    "type": "date",
                    "created_at": null,
                    "updated_at": null
                }
            },
            {
                "id": 80,
                "attribute_id": 4,
                "entity_id": 20,
                "entity_type": "App\\Models\\Project",
                "value": "31993",
                "created_at": "2025-03-11T13:45:44.000000Z",
                "updated_at": "2025-03-11T13:45:44.000000Z",
                "attribute": {
                    "id": 4,
                    "name": "budget",
                    "type": "number",
                    "created_at": null,
                    "updated_at": null
                }
            }
        ]
    }
]
```

### Timesheets

#### Get All Timesheets

```http
GET /api/timesheets
```

#### Get Timesheet by ID

```http
GET /api/timesheets/{id}
```

#### Create Timesheet

```http
POST /api/timesheets
```

#### Update Timesheet

```http
PUT /api/timesheets/{id}
```

#### Delete Timesheet

```http
DELETE /api/timesheets/{id}
```

---

### EAV: Dynamic Project Attributes

#### Create/Update Attributes

```http
POST /api/attributes
```

#### Set Attribute Values

```http
POST /api/attribute-values
```

#### Fetch Projects with Attributes

```http
GET /api/projects
```

#### Filter Projects by Attributes

```http
GET /api/projects?filters[department]=IT
```

---

## Test Credentials

Use these credentials for testing:

```
Email: test@example.com
Password: password123
```

---

## Deliverables

-   ***

## License

This project is open-source and available under the [MIT License](LICENSE).
