# FINSIGHT - Financial Management System

**FINSIGHT** is a web-based financial management application designed for university environments. It provides features for managing personal and institutional finances with role-based access control.

## Features

- **Transaction Management**: Record income and expenses
- **Budget Planning**: Set and track budgets
- **Financial Goals**: Define and monitor financial targets
- **Reporting**: View financial analytics and reports
- **Role-based Access**: Different permissions for Admin, Staff, and Students

## Prerequisites

- Docker and Docker Compose
- Git (optional, for cloning the repository)

## Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/Syarif-H55/FINSIGHT-1.git
   cd finsight
   ```

2. Build and start the services:
   ```bash
   docker-compose up -d
   ```

3. Access the application:
   - Web application: http://localhost:8080
   - phpMyAdmin: http://localhost:8081
   - MySQL: localhost:3306

## Database Setup

The database is automatically configured when you start the Docker containers. The initial setup includes:
- A default admin user with username `admin` and password `admin123`
- Sample transactions for demonstration

### Database Credentials
- Host: `db`
- Port: `3306`
- Database: `finsight`
- Username: `finsight_user`
- Password: `finsight_pass`

## Default Users

- **Admin User**: 
  - Username: `admin`
  - Password: `admin123`

## Project Structure

```
/finsight/
├── /docs/
│   └── /architecture/
├── /src/
│   ├── /core/
│   │   ├── config.php
│   │   ├── database.php
│   │   ├── auth.php
│   │   └── helpers.php
│   ├── /modules/
│   │   ├── /auth/
│   │   ├── /transactions/
│   │   ├── /budgets/
│   │   ├── /goals/
│   │   └── /reports/
│   ├── /templates/
│   ├── /assets/
│   └── index.php
├── /uploads/
├── /database/
├── docker-compose.yml
└── README.md
```

## Development

To run the application in development mode:

1. Make sure Docker is running
2. Navigate to the project directory
3. Run: `docker-compose up`
4. Access the application at http://localhost:8080

### Adding Sample Data

If you need to add more sample data, you can use phpMyAdmin at http://localhost:8081 or connect directly to the database.

## Security Notes

- All database queries use prepared statements to prevent SQL injection
- Passwords are hashed using PHP's password_hash() function
- Sessions use secure configurations
- Input validation and sanitization is implemented throughout the application

## Troubleshooting

- If you get a database connection error, make sure the db container is running: `docker-compose ps`
- To reset the database: `docker-compose down -v` then `docker-compose up -d`
- Check container logs: `docker-compose logs web` or `docker-compose logs db`

## Customization

- Modify templates in `/src/templates/` to change the look and feel
- Add new modules in `/src/modules/` following the existing structure
- Update CSS in `/src/assets/css/`
- Configure application settings in `/src/core/config.php`

## License

This project is licensed under the MIT License.