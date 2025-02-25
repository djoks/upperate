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
14. [API Documentation](#api-documentation)
15. [OpenAPI v3 Specification](#openapi-v3-pecification)

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
chmod +x run.sh
./run.sh
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
-   **Real-Time Reliability:** Monitoring and reconnection strategies could be improved.
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
├── run.sh
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
chmod +x run.sh
```

Run the Script:

```bash
./run.sh
```

What the Script Does:

-   Detects Your Shell: Automatically sets the Sail alias based on your current shell.
-   Installs Dependencies: Checks for composer.json and package.json to install Composer and NPM packages.
-   Starts Docker Containers: Launches the required Docker services using Sail.
-   Initialises the Database: Waits for the database connection to be ready and then runs migrations.

This unified script ensures a consistent setup experience across all platforms, making it easy to get started with the project.

## API Documentation

The Upperate application provides a REST API under the base URL `/api/v1/`. Two important endpoints are provided for testing and retrieving real-time cryptocurrency price data.

### 1. Broadcast Test Endpoint

This endpoint is used to simulate a cryptocurrency price update by dispatching a `CryptoPriceUpdated` event. Use this endpoint to verify that your broadcast listeners are working correctly.

#### Endpoint
```
GET /api/v1/broadcast
```

#### Description
- **Purpose:** Generates a random cryptocurrency price update.
- **Query Parameters:**
  - `pair` (string, optional): The cryptocurrency pair (default: `"BTCUSDC"`).
  - `exchange` (string, optional): The exchange name (default: `"binance"`).

#### How to Test
1. **Start the Application:**  
   Make sure your Laravel application is running (for example, using Sail):
   ```bash
   sail up -d
   ```

2. **Trigger the Broadcast:**  
   Open your browser or use `curl` to hit:
   ```
   http://localhost/api/v1/broadcast
   ```
   For example, with curl:
   ```bash
   curl http://localhost/api/v1/broadcast
   ```

3. **Observe the Results:**  
   Although this endpoint does not return a body, you can check your logs or your front-end (if it’s listening for the event) to verify that the broadcast was triggered.

### 2. Crypto Prices Endpoint

This endpoint returns the latest cryptocurrency prices grouped by exchange. It is used by the front-end to display real-time pricing information.

#### Endpoint
```
GET /api/v1/crypto-prices
```

#### Sample Response
```json
[
    {
        "exchange": "Binance",
        "prices": [
            {
                "pair": "BTCUSDC",
                "exchange": "Binance",
                "average_price": "92,674.14",
                "price_change": "0.13210362",
                "change_direction": "upward",
                "created_at": "2025-02-25 00:25:41",
                "updated_at": "2025-02-25 00:25:41"
            },
            {
                "pair": "BTCUSDT",
                "exchange": "Binance",
                "average_price": "92,693.58",
                "price_change": "0.33883462",
                "change_direction": "downward",
                "created_at": "2025-02-25 00:53:47",
                "updated_at": "2025-02-25 00:53:47"
            }
        ]
    },
    {
        "exchange": "Huobi",
        "prices": [
            {
                "pair": "BTCUSDC",
                "exchange": "Huobi",
                "average_price": "92,688.55",
                "price_change": "0.48528242",
                "change_direction": "downward",
                "created_at": "2025-02-25 00:53:46",
                "updated_at": "2025-02-25 00:53:46"
            },
            {
                "pair": "BTCUSDT",
                "exchange": "Huobi",
                "average_price": "99,154.33",
                "price_change": "0.25418225",
                "change_direction": "upward",
                "created_at": "2025-02-25 00:25:48",
                "updated_at": "2025-02-25 00:25:48"
            }
        ]
    }
]
```

---

## OpenAPI v3 Specification

Below is an excerpt of an OpenAPI v3 specification that documents these endpoints and describes each field in detail.

```yaml
openapi: 3.0.0
info:
  title: Upperate API
  version: 1.0.0
paths:
  /api/v1/broadcast:
    get:
      summary: Test Broadcast Endpoint
      description: Generates a random cryptocurrency price update and dispatches a CryptoPriceUpdated event.
      parameters:
        - in: query
          name: pair
          schema:
            type: string
          description: The cryptocurrency pair identifier (default: "BTCUSDC").
        - in: query
          name: exchange
          schema:
            type: string
          description: The exchange name (default: "binance").
      responses:
        '200':
          description: Broadcast event dispatched successfully.
  /api/v1/crypto-prices:
    get:
      summary: Retrieve Latest Cryptocurrency Prices
      description: Returns a list of the latest cryptocurrency price records grouped by exchange.
      responses:
        '200':
          description: A JSON array of cryptocurrency price data.
          content:
            application/json:
              schema:
                type: array
                items:
                  type: object
                  properties:
                    exchange:
                      type: string
                      description: The name of the cryptocurrency exchange.
                    prices:
                      type: array
                      description: A list of price records for the exchange.
                      items:
                        type: object
                        properties:
                          pair:
                            type: string
                            description: The cryptocurrency pair identifier.
                          exchange:
                            type: string
                            description: The exchange name (should match the parent exchange field).
                          average_price:
                            type: string
                            description: The average price formatted as a string.
                          price_change:
                            type: string
                            description: The price change, which may be positive or negative.
                          change_direction:
                            type: string
                            description: The direction of price change ("upward" or "downward").
                          created_at:
                            type: string
                            format: date-time
                            description: The timestamp when the record was created.
                          updated_at:
                            type: string
                            format: date-time
                            description: The timestamp when the record was last updated.
```

### Additional Notes

- **Base URL:** All endpoints are prefixed with `/api/v1/`.
- **Testing Broadcasts:** Use the `/api/v1/broadcast` route to simulate real-time updates.
- **Data Fields:** Each field is documented in the OpenAPI specification to ensure clarity on expected types and values.

This documentation should help developers quickly understand and test the API functionality in Upperate.
