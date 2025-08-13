@echo off
echo Starting WordPress Development Environment...
echo.

REM Check if Docker is available
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker is not installed or not in PATH.
    echo Please install Docker Desktop from https://www.docker.com/products/docker-desktop/
    echo.
    pause
    exit /b 1
)

echo Docker found. Starting services...
echo.

REM Start WordPress services
docker compose up -d

if %errorlevel% equ 0 (
    echo.
    echo WordPress is starting up!
    echo.
    echo Access your site at: http://localhost:8000
    echo Access phpMyAdmin at: http://localhost:8080
    echo.
    echo To stop services, run: docker compose down
    echo.
) else (
    echo.
    echo Failed to start services. Check the error messages above.
    echo.
)

pause
