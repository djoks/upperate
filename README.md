````markdown
# Upperate Project Setup Guide

Welcome to the Upperate project. This guide explains how to set up, configure, and run the application using Laravel with Sail and Reverb.

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Usage](#usage)
5. [Real-Time Features](#real-time-features)
6. [Architecture Overview](#architecture-overview)
7. [Design Decisions and Trade-offs](#design-decisions-and-trade-offs)
8. [Known Issues, Limitations and Improvements](#known-issues-limitations-and-improvements)
9. [Development Workflow](#development-workflow)
10. [Troubleshooting](#troubleshooting)
11. [Deployment](#deployment)
12. [Project Structure](#project-structure)
13. [Cross-Platform Setup Script](#cross-platform-setup-script)

---

## Prerequisites

Before you begin, ensure the following are installed:

-   **Docker Desktop** (v4.25 or later)
-   **PHP** (v8.2 or later)
-   **Composer** (v2.6 or later)
-   **Node.js** (v18 or later)
-   **Git** (v2.39 or later)
-   **Bash/Zsh** shell

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/djoks/upperate
cd upperate
```
````

### 2. Run the Setup Script

Make the script executable and run it:

```bash
chmod +x setup.sh
./setup.sh
```

This script will:

-   Configure the Sail alias.
-   Install PHP and NPM dependencies.
-   Start Docker containers.
-   Run database migrations.

---

## Configuration

### Environment Settings

1. Duplicate the example file:

    ```bash
    cp .env.example .env
    ```

2. Generate the application key:

    ```bash
    sail artisan key:generate
    ```

### Reverb Configuration

Update your `.env` file with the following:

```ini
BROADCAST_DRIVER=reverb
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
```

### Database Settings

Ensure your database settings in `.env` are as follows:

```ini
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=upperate
DB_USERNAME=sail
DB_PASSWORD=password
```

---

## Usage

### Starting the Application

Launch the development environment:

```bash
sail up -d
```

### Accessing Services

-   **Web Application:** [http://localhost](http://localhost)
-   **Reverb WebSocket:** ws://localhost:9002

---

## Real-Time Features

### Starting the Reverb Server

```bash
sail artisan reverb:start
```

### Building Frontend Assets

```bash
sail npm run dev
```

---

## Architecture Overview

The Upperate project is built on a modern web stack with Laravel at its core, and it integrates proven design patterns to enhance code clarity, scalability, and maintainability.

-   Laravel Framework: Acts as the backbone of the application, providing a robust, modular structure that speeds up development while maintaining high security standards.

-   Service Pattern: Business logic is encapsulated within dedicated service classes. This separation ensures that controllers remain lean and focused on handling requests, making it easier to test and maintain complex operations.

-   Repository Pattern: Data access is abstracted through repository classes. This design decouples data retrieval from business logic, allowing for effortless swapping or modification of data sources without disrupting the rest of the application.

-   Docker & Sail: These tools simplify the development environment by containerising dependencies, ensuring consistency across platforms.

-   Reverb: Manages real-time communications via WebSockets, enabling efficient event-driven interactions.

### Adherence to SOLID Principles:

-   Single Responsibility: Each class and method is dedicated to a specific function.
-   Open/Closed: Components are designed to be extendable without modifying existing code.
-   Dependency Inversion: High-level modules do not depend on low-level modules, which promotes flexibility and easier testing.
-   DRY Principle: Code is structured to avoid duplication by utilising reusable components and well-defined patterns, keeping the codebase clean and efficient.

This approach not only enhances maintainability and scalability but also ensures that the project remains robust and adaptable to future changes.

---

## Design Decisions and Trade-offs

-   **Framework Choice:** Laravel was chosen for its extensive ecosystem and ease of use. The trade-off is that it may introduce overhead compared to micro-frameworks.
-   **Containerisation:** Using Docker and Sail simplifies cross-platform development but can add complexity when troubleshooting container-specific issues.
-   **Real-Time Communication:** Reverb was integrated for simplicity in handling WebSocket events. However, this may limit customisation compared to bespoke real-time solutions.
-   **Development Simplicity vs. Performance:** Prioritising a straightforward setup and deployment workflow means some performance optimisations are deferred to future updates.

---

## Known Issues, Limitations and Improvements

-   **Port Conflicts:** Occasionally, Docker may conflict with existing services. Manual port reassignments might be necessary.
-   **Real-Time Reliability:** The WebSocket connection doesn't work as expected. Monitoring and reconnection strategies could be improved.
-   **Future Improvements:** Additional test coverage, enhanced logging, and optimisation of container startup times are planned.

---

## Development Workflow

Common commands include:

-   **Migrate Database:**  
    `sail artisan migrate`
-   **Run Tests:**  
    `sail artisan test`
-   **Watch Frontend Assets:**  
    `sail npm run dev`
-   **Stop Services:**  
    `sail down`

---

## Troubleshooting

### Common Issues

1. **Port Conflicts:**  
   Verify that ports 80, 3306, and 9002 are free:

    ```bash
    lsof -i :80
    ```

2. **Docker Issues:**  
   Clear Docker cache and rebuild:

    ```bash
    docker system prune
    sail build --no-cache
    ```

3. **Real-Time Failures:**  
   Ensure the Reverb server is running and check the browser console for WebSocket errors.

---

## Deployment

### Production Settings

1. Set `APP_ENV=production` in `.env`
2. Enable HTTPS for Reverb
3. Configure Redis appropriately
4. Set up queue workers:
    ```bash
    sail artisan queue:work --daemon
    ```

### Optimisation Commands

```bash
sail artisan config:cache
sail artisan route:cache
sail artisan view:cache
sail npm run build
```

---

## Project Structure

Below is a brief overview of the key project directories:

```
/upperate/
├── .env.example
├── README.md
├── setup.sh
├── app/
├── config/
├── database/
├── docker/
├── public/
├── resources/
├── routes/
└── [other files and directories]
```

---

## Cross-Platform Setup Script

The Upperate project includes a single script that works on Windows, macOS, and Linux, streamlining your setup process.

### Installation Instructions:

Ensure a Compatible Shell:

-   Windows: Use Git Bash, WSL, or another Unix-like shell.
-   macOS/Linux: Use your default terminal.
-   Make the Script Executable:

```bash
chmod +x setup.sh
```

Run the Script:

```bash
./setup.sh
```

What the Script Does:

-   Detects Your Shell: Automatically sets the Sail alias based on your current shell.
-   Installs Dependencies: Checks for composer.json and package.json to install Composer and NPM packages.
-   Starts Docker Containers: Launches the required Docker services using Sail.
-   Initialises the Database: Waits for the database connection to be ready and then runs migrations.

This unified script ensures a consistent setup experience across all platforms, making it easy to get started with the project.
