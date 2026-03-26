# CookSphere

CookSphere is a web application for sharing and discovering recipes.

## Folder Structure

```
project/
│── css/
│── js/
│── images/
│── includes/
│ ├── db.php             # Database connection file
│ ├── functions.php      # Helper functions (e.g., input validation)
│── auth/
│ ├── register.php       # User registration logic
│ ├── login.php          # User login logic
│ ├── logout.php         # Logout logic
│── contact.php          # Contact form
│── index.php            # Main page
│── dashboard.php        # User dashboard
│── README.md            # Project details and setup instructions
│── database.sql         # MySQL dump file
```

## Setup Instructions

1. Import `database.sql` into your MySQL server.
2. Update the credentials in `includes/db.php` if needed.
3. Serve the directory using a web server like Apache or Nginx (or tools like XAMPP/WAMP).
4. Access `index.php` in your browser.
