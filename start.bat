@echo off
echo Starting Laravel Development Server and Queue Worker...
echo.

:: Start the queue worker in background
start /B "Queue Worker" php artisan queue:work --tries=3 --timeout=60

:: Wait a moment for queue worker to start
timeout /t 2 /nobreak > nul

:: Start the development server (this will block)
echo Server started at http://127.0.0.1:8000
echo Queue worker is running in background
echo Press Ctrl+C to stop both services
echo.
php artisan serve

:: When serve is stopped, kill the queue worker
taskkill /FI "WINDOWTITLE eq Queue Worker*" /F > nul 2>&1
