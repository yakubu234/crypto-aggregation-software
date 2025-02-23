#!/bin/bash

echo "Starting WebSocket Server..."
php artisan reverb:start &

echo "Starting Queue Worker..."
php artisan horizon:start &

echo "Starting Schedule Worker..."
php artisan schedule:work &

echo "Starting Laravel Server..."
php artisan serve &

echo "Starting Webpack for LiveWire..."
npm run dev

echo "All services started successfully."
