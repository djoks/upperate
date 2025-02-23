````markdown
# Project Setup Guide

This documentation provides step-by-step instructions to set up the Laravel project with Sail and Reverb.

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Running the Application](#running-the-application)
5. [Real-Time Setup](#real-time-setup)
6. [Development Workflow](#development-workflow)
7. [Troubleshooting](#troubleshooting)
8. [Deployment Notes](#deployment-notes)

---

## Prerequisites

-   **Docker Desktop** (v4.25+)
-   **PHP** (8.2+)
-   **Composer** (2.6+)
-   **Node.js** (18+)
-   **Git** (2.39+)
-   **Bash/Zsh** shell

---

## Installation

### 1. Clone Repository

```bash
git clone [your-repository-url]
cd your-project-name
```
````

### 2. Run Setup Script

```bash
chmod +x setup.sh
./setup.sh
```

This script will:

1. Configure Sail alias
2. Install PHP dependencies
3. Install NPM packages
4. Start Docker containers
5. Run database migrations

---

## Configuration

### Environment Setup

1. Copy example environment file:

```bash
cp .env.example .env
```

2. Generate application key:

```bash
sail artisan key:generate
```

3. Configure Reverb in `.env`:

```ini
BROADCAST_DRIVER=reverb
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
```

### Database Configuration

Verify database settings in `.env`:

```ini
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

---

## Running the Application

### Start Development Environment

```bash
sail up -d
```

### Access Services:

| Service         | URL                 |
| --------------- | ------------------- |
| Web Application | http://localhost    |
| Reverb WS       | ws://localhost:8080 |

---

## Real-Time Setup

### 1. Start Reverb Server

```bash
sail artisan reverb:start
```

### 3. Build Frontend Assets

```bash
sail npm run dev
```

---

## Development Workflow

### Common Commands

```bash
# Run migrations
sail artisan migrate

# Run tests
sail artisan test

# Watch frontend assets
sail npm run dev
```

### Stopping Services

```bash
sail down
```

---

## Troubleshooting

### Common Issues

1. **Port Conflicts**:

    - Check ports 80, 3306, 9002

    ```bash
    lsof -i :80
    ```

2. **Docker Issues**:

    ```bash
    docker system prune
    sail build --no-cache
    ```

3. **Realtime Failures**:
    - Verify Reverb server is running
    - Check WebSocket connection in browser console

---

## Deployment Notes

### Production Requirements

1. Set `APP_ENV=production` in `.env`
2. Configure HTTPS for Reverb
3. Set up proper Redis configuration
4. Configure queue workers:

```bash
sail artisan queue:work --daemon
```

### Optimization

```bash
sail artisan config:cache
sail artisan route:cache
sail artisan view:cache
sail npm run build
```

---

**Note:** Always review the `setup.sh` script before running it in production environments.
