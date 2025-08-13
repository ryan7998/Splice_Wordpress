# WordPress Setup Instructions

## Method 1: Docker (Recommended)

### Prerequisites

- Install Docker Desktop from [docker.com](https://www.docker.com/products/docker-desktop/)
- Make sure Docker is running

### Setup Steps

1. Open terminal/command prompt in this directory
2. Run: `docker compose up -d`
3. Wait for services to start
4. Access WordPress at: http://localhost:8000
5. Complete WordPress installation

## Method 2: Manual WordPress Download

### Prerequisites

- PHP 7.4+ installed
- MySQL/MariaDB installed
- Web server (Apache/Nginx) or PHP built-in server

### Setup Steps

1. Download WordPress from [wordpress.org](https://wordpress.org/download/)
2. Extract to this directory
3. Copy `wp-config-sample.php` to `wp-config.php`
4. Edit `wp-config.php` with your database details
5. Create database and run WordPress installation

## Method 3: XAMPP/WAMP

### Prerequisites

- Install XAMPP or WAMP
- Start Apache and MySQL services

### Setup Steps

1. Copy this project to your web server directory
2. Download WordPress and extract
3. Configure database in `wp-config.php`
4. Access via localhost

## Current Project Structure

```
Wordpress/
├── wp-content/
│   └── themes/
│       └── custom-theme/          # Your custom theme (to be created)
├── docker-compose.yml             # Docker configuration
├── .gitignore                     # Git ignore rules
├── README.md                      # Project documentation
└── setup-instructions.md          # This file
```

## Next Steps

Once WordPress is running:

1. We'll create a custom theme from scratch
2. Set up Git repository
3. Develop theme features step by step

## Database Configuration (if not using Docker)

If setting up manually, you'll need these database settings:

- Database Name: `wordpress`
- Username: `wordpress`
- Password: `wordpress_password`
- Database Host: `localhost`
- Table Prefix: `wp_`
