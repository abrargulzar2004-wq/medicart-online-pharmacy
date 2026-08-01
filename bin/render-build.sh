#!/usr/bin/env bash
# Exit immediately if a command exits with a non-zero status.
set -o errexit

echo "========================================"
echo "Starting MediCart Render Build..."
echo "========================================"

echo ""
echo "Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

echo ""
echo "Caching Laravel configuration..."
php artisan config:cache

echo ""
echo "Caching routes..."
php artisan route:cache

echo ""
echo "Caching views..."
php artisan view:cache

echo ""
echo "Running database migrations..."
php artisan migrate --force

echo ""
echo "Seeding database..."
php artisan db:seed --force

echo ""
echo "Creating storage symlink..."
php artisan storage:link || true

echo ""
echo "========================================"
echo "Build completed successfully!"
echo "========================================"