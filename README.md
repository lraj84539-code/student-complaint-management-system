# CampusCare — Student Complaint Management System

CampusCare is a simple, professional PHP and MySQL academic project for submitting, tracking, and resolving student complaints. It follows the supplied BCA 4th-semester requirements without React, Node.js, Express, MongoDB, or other application frameworks.

## Features

Students can register and sign in, submit complaints by category and priority, attach supporting documents, search and filter their own complaints, follow status changes, read administrator remarks, and update their profile. Administrators have a separate login, dashboard statistics, complaint search and filtering, status and remark management, registered-student listings, and category management.

The implementation uses PHP sessions, `password_hash()` / `password_verify()`, prepared MySQL statements, CSRF tokens, escaped output, role checks, and extension/size validation for uploads. The upload directory is intentionally ignored by Git except for its placeholder file.

## Requirements

Use PHP 8.1 or newer, MySQL 8 or MariaDB 10.4+, and a web server such as Apache with PHP enabled. Bootstrap 5 is loaded from its public CDN for responsive layout.

## Setup

1. Create a MySQL database by importing `database/schema.sql`.
2. Copy `config/local.php.example` to `config/local.php` and set the database credentials. The local configuration is ignored by Git.
3. Place the project in the web server document root, or run it through Apache/XAMPP/WAMP.
4. Ensure the `uploads/` directory is writable by PHP.
5. Open `http://localhost/student-complaint-management-system/` or the matching local URL.

The seeded administrator account is `admin@example.com` with password `Admin@12345`. Change or remove this account before production use.

## Project map

`auth/` contains login, registration, and logout flows. `student/` contains the student dashboard and complaint pages. `admin/` contains administrator dashboards and management screens. `config/` contains database configuration. `includes/` contains shared helpers and layout templates. `database/schema.sql` creates the database, tables, relationships, indexes, categories, and demo administrator. `assets/` contains CSS and JavaScript.

## Security notes

Use HTTPS in production, replace the demo administrator password, keep `config/local.php` private, restrict direct execution of uploaded files at the web-server level, and review file MIME handling before production deployment. This repository contains no real student data or production credentials.
