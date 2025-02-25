#!/bin/bash

# Store PIDs
pids=()

# Function to terminate background processes
terminate_processes() {
    echo "Terminating background processes..."
    for pid in "${pids[@]}"; do
        if kill -0 "$pid" 2>/dev/null; then # Check if process exists
            kill "$pid"
        fi
    done
    exit 0
}

# Trap interrupt signals (Ctrl+C)
trap terminate_processes INT TERM

echo "Starting WebSocket Server..."
php artisan reverb:start &
pids+=("$!")

echo "Starting Queue Worker..."
php artisan horizon &
pids+=("$!")

echo "Starting Schedule Worker..."
php artisan schedule:work &
pids+=("$!")

echo "Starting Laravel Server..."
php artisan serve &
pids+=("$!")

echo "Starting Webpack for LiveWire..."
npm run dev &
pids+=("$!")

echo "All services started successfully."

# Wait for background processes (optional)
# wait "${pids[@]}"

# Keep the script running until interrupted
while true; do
  sleep 1
done