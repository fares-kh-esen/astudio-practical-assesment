# Backend Developer Assessment

This project is a Laravel-based backend application that implements core models, EAV (Entity-Attribute-Value) dynamic attributes, RESTful API endpoints, and a flexible filtering system. It uses Laravel Passport for authentication and follows PSR standards and Laravel best practices.

---

## Features

-   User authentication (Register, Login, Logout) using Laravel Passport
-   Manage Users, Projects, and Timesheets
-   Entity-Attribute-Value (EAV) system for dynamic project attributes
-   Flexible filtering system supporting both regular and EAV attributes
-   RESTful API with proper validation and error handling

---

## Setup Instructions

### 1. Prerequisites

- PHP >= 8.0
- Composer
- MySQL
- Laravel Passport (for authentication)

### 2. Clone the Repository

```bash
git clone https://github.com/fares-kh-esen/astudio-practical-assesment.git
cd astudio-practical-assesment
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Configure Environment

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

Update `.env` file with your database and mail settings.

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Set Up Database

```bash
php artisan migrate --seed
```

### 7. Generating a Personal Access Client
-   To generate a Personal Access Client for issuing personal access tokens, run the following Artisan command:

```bash
php artisan passport:client --personal
```

## What This Command Does

-   Creates a Personal Access Client in the oauth_clients table.

-   This client is used to issue personal access tokens for authenticated users.

-   Personal access tokens are long-lived and do not expire unless manually revoked.



### 8. Start the Server

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


## Test Credentials

Use these credentials for testing:

```
Email: test@example.com
Password: password123
```

---

## Deliverables

-   ***
