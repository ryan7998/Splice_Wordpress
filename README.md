# WordPress Development Environment

This project sets up a WordPress development environment using Docker.

## Quick Start

1. Make sure you have Docker and Docker Compose installed
2. Run `docker-compose up -d` to start the environment
3. Access WordPress at `http://localhost:8000`
4. Access phpMyAdmin at `http://localhost:8080`

## Services

- **WordPress**: Available at http://localhost:8000
- **MySQL Database**: Available at localhost:3306
- **phpMyAdmin**: Available at http://localhost:8080

## Database Credentials

- Database: `wordpress`
- Username: `wordpress`
- Password: `wordpress_password`
- Root Password: `somewordpress`

## Development

The `wp-content` directory is mounted as a volume, so you can edit theme and plugin files directly from your host machine.

## Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f

# Access WordPress container
docker-compose exec wordpress bash
```
