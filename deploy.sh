echo "=== ENV ==="
printenv | grep OCTANE

echo "=== Memory Info ==="
free -m

# Clear Laravel caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Rebuild config cache to load Railway ENV properly
php artisan config:cache

# Log what port is being used
echo "Starting Octane on port ${PORT}"

# Start Octane with low memory settings
php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=${PORT} --workers=2 --max-requests=80