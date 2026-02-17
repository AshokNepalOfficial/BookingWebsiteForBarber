#!/bin/bash

echo "Starting Laravel Development Server and Queue Worker..."
echo ""

# Start the queue worker in background
php artisan queue:work --tries=3 --timeout=60 &
QUEUE_PID=$!

# Wait a moment for queue worker to start
sleep 2

# Function to cleanup on exit
cleanup() {
    echo ""
    echo "Stopping queue worker..."
    kill $QUEUE_PID 2>/dev/null
    echo "Stopped."
    exit 0
}

# Trap Ctrl+C and cleanup
trap cleanup SIGINT SIGTERM

# Start the development server
echo "Server started at http://127.0.0.1:8000"
echo "Queue worker is running in background (PID: $QUEUE_PID)"
echo "Press Ctrl+C to stop both services"
echo ""
php artisan serve

# Cleanup when serve stops
cleanup
