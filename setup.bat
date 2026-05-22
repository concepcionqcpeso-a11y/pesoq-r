@echo off
echo ======================================
echo PESOQ Setup - Installing Dependencies
echo ======================================
echo.

echo Installing Backend Dependencies (Composer)...
cd backend
call composer install
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
