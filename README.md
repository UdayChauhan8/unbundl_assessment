# Unbundl Assessment - PHP Application

**Submitted by:** Uday Chauhan

---

## Prerequisites

- PHP installed locally
- MySQL database server running

---

## Setup Instructions

### 1. Create the Database
Import the provided `schema.sql` file into your MySQL server to create the `unbundl_db` database and the `users` table.

```bash
mysql -u root -p < schema.sql
```

### 2. Configure Database Credentials
Open `db.php`. By default, it is configured to use:

- **Host:** 127.0.0.1
- **Username:** root
- **Password:** root

> Please update these credentials if your local MySQL setup differs.

### 3. Run the Application
Start a local PHP server in the project directory.

```bash
php -S localhost:8000
```

### 4. View the App
Open [http://localhost:8000/index.php] in your web browser.
