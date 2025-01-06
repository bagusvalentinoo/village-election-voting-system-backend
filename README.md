# Backend Village Election Voting System

## 📝 Description

This repository contains the backend codebase for a web-based electronic voting system specifically designed for village head elections. Built with **Laravel 11**, this backend ensures scalability, security, and efficiency to support the voting process.

> **Note:** This project serves as the backend counterpart for the [village-election-voting-system-frontend](https://github.com/bagusvalentinoo/village-election-voting-system-frontend). Ensure the frontend is configured properly for full functionality.

---

## ✨ Features

-   Secure voter authentication and authorization.
-   Real-time vote counting and monitoring.
-   Role-based access control for administrators and voters.
-   Candidate and voter management system.
-   RESTful API for seamless integration with the frontend.
-   Scalable database architecture using **PostgreSQL**.

---

## 🔧 Requirements

-   **PHP** 8.2 or higher
-   **Composer**
-   **PostgreSQL** 13 or higher

---

## 🚀 Installation

Follow these steps to set up the project locally:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/bagusvalentinoo/voting-system-for-village-head-elections-backend.git
    ```

2. **Navigate to the project directory:**

    ```bash
    cd village-election-voting-system-backend
    ```

3. **Install dependencies:**

    ```bash
    composer install
    ```

4. **Configure environment variables:**

    - Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    - Update `.env` with your database and other environment settings.

5. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

6. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

7. **Start the development server:**

    ```bash
    php artisan serve
    ```

8. **Access the application:**
   Open your browser and navigate to `http://localhost:8000`.

---

## 📡 API Documentation

The API documentation is available on Postman. Access it here:  
[Postman API Documentation](https://documenter.getpostman.com/view/7865721/2sA3QwbUwa)

---

## 📜 License

This project is licensed under the [MIT License](LICENSE).

---

## 📞 Support

If you encounter any issues or need assistance, feel free to open an issue or contact the maintainers.

---

## 📚 Resources

-   [Laravel Documentation](https://laravel.com/docs)
-   [PostgreSQL Documentation](https://www.postgresql.org/docs/)
-   [Composer Documentation](https://getcomposer.org/doc/)
