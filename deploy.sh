#!/bin/bash

# Make sure any failure causes exit
set -e

# Clear and rebuild Laravel caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan config:cache

# Log port
echo "Starting Octane on port ${PORT}"

# Install RoadRunner manually (optional if not present)
# curl -Ls https://github.com/roadrunner-server/roadrunner/releases/latest/download/roadrunner-linux-amd64 -o rr
# chmod +x rr

# Block and keep server alive
exec php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=${PORT} --workers=2 --max-requests=80