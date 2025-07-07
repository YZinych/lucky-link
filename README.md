# LuckyLink App

LuckyLink is a Laravel-based app for generating one-time game links.
Players receive a random number and can win a prize based on configurable win rules.
Each link expires after a set time, but users can regenerate a new one if needed.
Expired links are automatically deactivated, and users can view the history of their recent attempts.

<a href="https://lucky-link-app.travels-zone.com" target="_blank" style="padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Live Demo</a>

## Features

- Token-based game system with expiry
- Win logic based on configurable rules
- Queued jobs and scheduled tasks
- Redis caching and queue management
- Unit Tests
- Tech Stack: Laravel 11 + PHP 8.3 + Docker + Nginx + MySQL
- Docker-ready - easy setup with Docker Compose

## Requirements

- Docker & Docker Compose
- GNU Make (optional but recommended)

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/YZinych/lucky-link.git .
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Copy .env settings

You can use the default configuration for quick local setup:

```bash
cp .env.example .env
```

> Make sure to do this **before** building Docker images — `.env` is required during the build process.

### 4. Build Docker Images

```bash
docker-compose build --no-cache
```

### 5. Start the Services

When you start all containers, **all necessary services** are launched automatically, including the **Queue Worker** and **Scheduler**.
Start in detached mode:

```bash
docker-compose up -d
```

### 6. Run Database Migrations

```bash
docker compose exec app php artisan migrate
```

### 7. Run Unit Tests (optional)

```bash
make test
```

### 8. Open in Browser
This is the default URL exposed by the Docker setup.
Visit: [http://localhost:816/](http://localhost:816/)
