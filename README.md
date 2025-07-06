# LuckyLink App

LuckyLink is a Laravel-based app for generating one-time game links.
Players receive a random number and can win a prize based on configurable win rules.
Each link expires after a set time, but users can regenerate a new one if needed.
Expired links are automatically deactivated, and users can view the history of their recent attempts.

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

Clone the repository into your local project folder:

```bash
git clone https://github.com/YZinych/lucky-link.git .
```

### 2. Build Docker Images

```bash
docker-compose build --no-cache
```
Or use the Makefile shortcut:
```bash
make dev-b
```

During this step, Docker builds the app container and automatically:

- Installs PHP dependencies
- Copies `.env.example` to `.env` (if missing)
- Generates a new `APP_KEY`
- Caches config and routes

### 3. Start the Services

When you start all containers, **all necessary services** are launched automatically, including the **Queue Worker** and **Scheduler**.

Start in detached mode:

```bash
docker-compose up -d
```

Or use the Makefile shortcut:

```bash
make dev
```

### 4. Run Database Migrations

Create all necessary database tables:

```bash
docker compose exec app php artisan migrate
```

Or use the Makefile shortcut:

```bash
make migrate
```

### 5. Run Unit Tests (optional)

To run unit tests, use the following command inside the `app` container:

```bash
make test
```
