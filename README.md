# CIT-U CCS — Issue Reporting System

![Language](https://img.shields.io/badge/Language-PHP%20%26%20SQL-blue)
![Status](https://img.shields.io/badge/Status-Completed-success)
![Course](https://img.shields.io/badge/Course-CSIT--226-blue)
![Focus](https://img.shields.io/badge/Focus-Information%20Management-green)

A lightweight issue reporting system for the College of Computer Studies at CIT-U. This project demonstrates basic CRUD operations, relational design, and a simple admin interface to manage reported computer lab issues.

**Highlights**
- Submit and view computer lab issue reports
- Admin panel to update statuses and delete reports
- MySQL schema with users, rooms, and issue reports
- Simple, responsive UI using HTML/CSS and minimal JavaScript

## Quick Demo
1. Register or login as a user.
2. Submit an issue from the `dashboard` page.
3. View all reports on `readrecords.php`.
4. Admin users can manage reports at `admin_record.php`.

## Repository Structure

| File / Folder | Purpose |
| :--- | :--- |
| `computer_issue_system.sql` | Full database schema and sample data (import to MySQL). |
| `connect.php` | Database connection configuration. Update with your credentials. |
| `register.php` | User registration form and logic. |
| `login.php` | Login form and session initialization. |
| `dashboard.php` | Submit new issue reports. |
| `readrecords.php` | View all submitted reports (and endpoint for inserts). |
| `admin_record.php` | Admin panel — update statuses and delete reports. |
| `css/style.css` | Project styles. |

## Tech Stack
- PHP (procedural)
- MySQL / MariaDB
- HTML, CSS, vanilla JavaScript

## Requirements
- XAMPP (or any PHP + MySQL environment)
- PHP 7.4+ recommended

## Setup (local)
1. Place the project folder in your web root (e.g., `c:\xampp\htdocs\computer_issue_system`).
2. Start Apache and MySQL (XAMPP control panel).
3. Import the database using phpMyAdmin or the MySQL CLI:

```sql
-- via mysql CLI
mysql -u root -p < computer_issue_system.sql
```

4. Edit `connect.php` to match your DB credentials.
5. Open your browser: `http://localhost/computer_issue_system/` (redirects to login).

## Configuration
- `connect.php`: update `$servername`, `$username`, `$password`, and `$dbname` if different.

## Security Notes & Recommendations
- The current project stores users without passwords and uses raw SQL queries — this is for demonstration only. Before production use, implement:
	- Strong authentication with hashed passwords (`password_hash` / `password_verify`).
	- Prepared statements to prevent SQL injection.
	- Output escaping (`htmlspecialchars`) to prevent XSS.
	- CSRF tokens for state-changing requests.
	- Use HTTPS and secure session cookie options.

---
_Created for CSIT-226 Information Management 1 — database & CRUD demonstration_
