# Clear Laravel caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear


php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=${PORT}
