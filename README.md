# Laravel Docker Compose Setup

This repository contains a Docker Compose configuration for setting up a Laravel development environment. The setup includes the following services:

- PHP 8.1 with Apache
- MySQL 5.7
- phpMyAdmin

## Prerequisites

- Docker and Docker Compose installed on your machine
- A Laravel project codebase

## Getting Started

### 1. Clone the Repository

Clone this repository or copy the `Dockerfile` and `docker-compose.yml` to your Laravel project directory.

### 2. Build and Run the Containers

Open a terminal and navigate to the directory containing your `Dockerfile` and `docker-compose.yml`. Then run:

```bash
docker-compose up -d
```

Or use make command

```bash
make setup
```

### 3. Generate component and key for Laravel
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```
### 4. Access Laravel Project
Go to
```bash
localhost:4040
```
