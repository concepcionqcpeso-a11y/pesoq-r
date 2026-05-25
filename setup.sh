#!/bin/bash

echo "======================================"
echo "PESOQ Setup - Installing Dependencies"
echo "======================================"
echo ""

echo "Installing Backend Dependencies (Composer)..."
cd backend
composer install

echo "Setting Up Environment..."
if [ ! -f .env ]; then
  echo "Creating .env file from template..."
  cp .env.example .env
  echo "Generating Application Key..."
  php artisan key:generate
else
  echo ".env file already exists. Skipping..."
fi

echo "Setting Up Database..."
if [ ! -f database/database.sqlite ]; then
  echo "Creating SQLite database file..."
  touch database/database.sqlite
  echo "Running Migrations & Seeders..."
  php artisan migrate:fresh --seed
else
  echo "Database already exists. Skipping migrations..."
fi

cd ..
echo ""

echo "Installing Frontend Dependencies (NPM)..."
cd frontend
npm install
cd ..
echo ""

echo "======================================"
echo "Setup Complete!"
echo "======================================"
echo ""
echo "To start development:"
echo "  Backend:  cd backend && php artisan serve"
echo "  Frontend: cd frontend && npm run dev"
