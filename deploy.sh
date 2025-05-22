# Clear Laravel caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear


echo "Starting Laravel Octane with RoadRunner on port ${PORT}..."

php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=${PORT} --max-requests=80