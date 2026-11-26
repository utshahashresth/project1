# Personal Finance Tracker

A PHP + MySQL web application for tracking income, expenses and budgets, with charts and a transaction history. Includes user registration with email verification.

## Features

- **Authentication:** sign-up and login with hashed passwords (`password_hash` / `password_verify`) and PHP sessions
- **Email verification:** verification link sent through PHPMailer (Gmail SMTP)
- **Income and expense tracking:** add transactions with category, amount, date and notes
- **Budgets:** create, update and delete budgets and view budget status
- **Dashboard and summary:** balance overview and balance trend charts built with Chart.js
- **History:** paginated transaction history

## Tech Stack

| Layer | Tools |
| --- | --- |
| Backend | PHP, MySQL (mysqli prepared statements) |
| Frontend | HTML, CSS, JavaScript, Chart.js, Font Awesome |
| Email | PHPMailer (via Composer) |

## Project Structure

```
backend/    # PHP endpoints: auth, inserts, fetches, budgets, chart data
frontend/   # Pages (home, budget, history, summary, stats, settings) plus CSS/JS
composer.json
```

## Getting Started

### Prerequisites

- PHP 8.x, MySQL/MariaDB, Composer
- A local server such as XAMPP or the built-in PHP server

### Setup

1. Clone the repository into your web root (for example `htdocs/project1`):

   ```bash
   git clone https://github.com/utshahashresth/project1.git
   cd project1
   composer install
   ```

2. Create a MySQL database (default name `project`) with the tables the app reads and writes: `register`, `income`, `expense`, `income_categories`, `expense_categories` and `budgets`.

3. Create your local config (this file is git-ignored):

   ```bash
   cp backend/config.example.php backend/config.php
   ```

   Then edit `backend/config.php` with your database credentials and a Gmail **App Password** for verification emails.

4. Open `http://localhost/project1/frontend/login.php`.

## Security Notes

- Credentials live in `backend/config.php`, which is excluded from version control.
- Queries use prepared statements; passwords are stored hashed.
- Planned hardening: CSRF tokens, stricter input validation, and a bundled SQL schema file.

## Roadmap

- Add `schema.sql` so the database can be created in one step
- Export transactions to CSV
- Recurring transactions and monthly reports
