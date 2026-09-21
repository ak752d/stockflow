# StockFlow

Inventory and order management system. Portfolio project for a PHP and React developer interview.

## Stack

- Backend: PHP 8.2, CodeIgniter 4, MySQL 8, JWT auth
- Frontend: React, Vite, Bootstrap, Chart.js
- Infra: Docker Compose

## Features

- JWT login with admin / staff roles
- Category and product CRUD (admin writes)
- Order creation with SELECT FOR UPDATE stock deduction
- Order status flow, including cancel (restores stock)
- Dashboard with stock and order charts

## Run locally

1. Create a root .env file:

MYSQL_DATABASE=stockflow
MYSQL_USER=stockflow
MYSQL_PASSWORD=stockflow
MYSQL_ROOT_PASSWORD=rootsecret

2. Copy backend/env to backend/.env and set:

CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.hostname = db
database.default.database = stockflow
database.default.username = stockflow
database.default.password = stockflow
database.default.DBDriver = MySQLi
database.default.port = 3306
JWT_SECRET = generate-a-long-random-string

3. Start and migrate:

docker compose up -d --build
docker compose exec app php spark migrate
docker compose exec app php spark db:seed AdminSeeder

Frontend: http://localhost:5173
API: http://localhost:8080

Demo login: admin@stockflow.test / Admin123!
