# Laravel Development Server with Queue Worker
Write-Host "Starting Laravel Development Server and Queue Worker..." -ForegroundColor Green
Write-Host ""

# Start queue worker in background
$queueJob = Start-Job -ScriptBlock {
    Set-Location $using:PWD
    php artisan queue:work --tries=3 --timeout=60
}

Write-Host "Queue worker started (Job ID: $($queueJob.Id))" -ForegroundColor Yellow

# Wait for queue worker to initialize
Start-Sleep -Seconds 2

Write-Host ""
Write-Host "Server started at http://127.0.0.1:8000" -ForegroundColor Cyan
Write-Host "Queue worker is running in background" -ForegroundColor Cyan
Write-Host "Press Ctrl+C to stop both services" -ForegroundColor Yellow
Write-Host ""

# Start development server (blocks until stopped)
try {
    php artisan serve
}
finally {
    # Cleanup: Stop queue worker when server stops
    Write-Host ""
    Write-Host "Stopping queue worker..." -ForegroundColor Yellow
    Stop-Job -Job $queueJob
    Remove-Job -Job $queueJob
    Write-Host "Stopped." -ForegroundColor Green
}
