# Backend Village Election Voting System

## Description

This repository contains the backend codebase for a web-based electronic voting system designed specifically for village
head elections. The system is built using Laravel 11, a powerful PHP framework for web application development.

## Requirements

- PHP 8.2 or higher
- Composer
- PostgreSQL 13 or higher

## Installation

1. Clone this repository to your local machine:
    ```
    git clone https://github.com/ExeCiety/voting-system-for-village-head-elections-backend.git
    ```

2. Navigate to the project directory:
    ```
    cd village-election-voting-system-backend
    ```

3. Install dependencies using Composer:
    ```
    composer install
    ```

4. Copy the `.env.example` file to `.env` and configure your environment variables, including your database connection
   details:
    ```
    cp .env.example .env
    ```

5. Generate an application key:
    ```
    php artisan key:generate
    ```

6. Migrate the database:
    ```
    php artisan migrate
    ```

7. Serve the application:
    ```
    php artisan serve
    ```

8. Access the application in your web browser at `http://localhost:8000`

## API Documentation

Postman Documenter
https://documenter.getpostman.com/view/7865721/2sA3QwbUwa
