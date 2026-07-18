# 🎓 Exam Hall Seating Management System

Exam Hall Seating Management System is a clean, responsive PHP and MySQL web application designed to automate the process of student seating allocation in exam halls, preventing search confusion and optimizing room capacity. It features three primary user portals: Student, Teacher, and Administrator.

---

## 📸 Screenshots

Here are previews of the modernized user interfaces for different portals and views in the system:


### 📊 Dashboards & Seating Arrangements
*   **Admin Seating Management Dashboard**:
    ![Admin Seating Dashboard](screenshoots/Screenshot%20from%202026-07-19%2000-07-35.png)
*   **Teacher Panel**:
    ![Teacher Panel](screenshoots/Screenshot%20from%202026-07-19%2000-10-18.png)
*   **Student Booking Details**:
    ![Student Booking](screenshoots/Screenshot%20from%202026-07-19%2000-10-30.png)
*   **Arrangement Details & Reports**:
    ![Arrangement Reports](screenshoots/Screenshot%20from%202026-07-19%2000-11-01.png)

---

## 🛠️ Technology Stack

*   **Language**: PHP (v8.1+ compatible)
*   **Database**: MySQL v8.0 / MariaDB
*   **Front-End**: Bootstrap, HTML5, CSS3
*   **Web Server**: Apache HTTP Server
*   **Containerization**: Docker & Docker Compose

---

## 🔑 Default Login Credentials

Once the system builds and seeds the database, you can log in using any of the following roles:

### 1. Administrator Profile
*   **Login URL**: `/login.php` (or `/index.php`)
*   **Username**: `admin`
*   **Password**: `123`

### 2. Teacher Profile
*   **Login URL**: `/teacher-login.php`
*   **Email**: `tr@seating.com`
*   **Password**: `1234567`

### 3. Student Profile
*   **Login URL**: `/student-login.php`
*   **Registration Number**: `2343/2024`
*   **Password**: `1234`

---

## 🚀 Deployment Instructions

You can run this project either using **Docker Compose** (recommended for quick setup) or **manually** on a local LAMP stack.

### Option A: Running with Docker Compose (Recommended)

1.  **Prerequisites**: Ensure you have [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/) installed.
2.  **Clone the Repository**:
    ```bash
    git clone git@github.com:gasanashema/Exam-Hall-Seating-management.git
    cd Exam-Hall-Seating-management
    ```
3.  **Build and Start Containers**:
    Run the following command to build the web server and launch the database:
    ```bash
    docker compose up --build -d
    ```
4.  **Access the Application**:
    Open your browser and navigate to:
    `http://localhost`

---

### Option B: Manual Installation (LAMP/WAMP/MAMP Stack)

1.  **Prerequisites**:
    *   Apache Server
    *   PHP (v8.1 or higher) with `mysqli` extension enabled
    *   MySQL/MariaDB Server
2.  **Move Source Files**:
    Copy all files in the project root directory to your web server document root (e.g., `/var/www/html/` or `htdocs/`).
3.  **Configure Database**:
    *   Log in to your MySQL command line or phpMyAdmin.
    *   Create a database named `examhall`:
        ```sql
        CREATE DATABASE examhall;
        ```
    *   Import the seed file `database/examhall.sql`:
        ```bash
        mysql -u [username] -p examhall < database/examhall.sql
        ```
4.  **Update Database Connection Settings**:
    If your local credentials differ from the defaults, edit `db_connection.php` at the root of the project:
    ```php
    $servername = "localhost";
    $username = "your_mysql_username";
    $password = "your_mysql_password";
    $dbname = "examhall";
    ```
5.  **Run the Server**:
    Navigate to `http://localhost/Exam-Hall-Seating-management/` in your web browser.

---

## 📂 Project Structure

```plaintext
├── css/                        # Front-end Bootstrap and styling files
├── database/                   # Database SQL files
│   ├── examhall.sql            # Core database seed and structure
├── includes/                   # Common component includes (e.g., menus, headers)
├── Dockerfile                  # Web container definition
├── docker-compose.yml          # Services orchestration configuration
├── db_connection.php          # Database link credentials and configuration
├── login.php                   # Admin portal entry point
├── student-login.php           # Student portal entry point
├── teacher-login.php           # Teacher portal entry point
├── student-home.php            # Student main workspace page
├── teachers_home.php           # Teacher main workspace page
├── home.php                    # Admin main workspace page
└── README.md                   # Project documentation
```

---

## ⚡ Query Optimization & N+1 Queries Fix

Optimized application database efficiency by replacing iterative database calls inside loops with pre-fetched associative arrays, resolving performance bottlenecks on:
1.  **Student Seating Plan page** ([student-home.php](file:///data/projects/other/Exam-Hall-Seating-management/student-home.php)): Consolidated student seating lookups.
2.  **Arrangements view page** ([created_arrangements.php](file:///data/projects/other/Exam-Hall-Seating-management/created_arrangements.php)): Pre-fetched student seating details to completely avoid nested queries.
3.  **Teacher Arrangements view page** ([teacher_exam_arrangements_created.php](file:///data/projects/other/Exam-Hall-Seating-management/teacher_exam_arrangements_created.php)): Optimized lists of exam seats.
