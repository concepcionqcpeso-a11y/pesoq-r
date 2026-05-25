@echo off
echo ======================================
echo PESOQ Setup - Installing Dependencies
echo ======================================
echo.

echo Installing Backend Dependencies (Composer)...
cd backend
call composer install

echo Setting Up Environment...
if not exist .env (
  echo Creating .env file from template...
  copy .env.example .env
  echo Generating Application Key...
  call php artisan key:generate
) else (
  echo .env file already exists. Skipping...
)

echo Setting Up Database...
if not exist database\database.sqlite (
  echo Creating SQLite database file...
  type nul > database\database.sqlite
  echo Running Migrations & Seeders...
  call php artisan migrate:fresh --seed --force
) else (
  echo Database already exists. Skipping migrations...
)

cd..
echo.

echo Installing Frontend Dependencies (NPM)...
cd frontend
call npm install
cd..
echo.

echo ======================================
echo Setup Complete!
echo ======================================
echo.
echo To start development:
echo   Backend:  cd backend && php artisan serve
echo   Frontend: cd frontend && npm run dev
echo.
pause
