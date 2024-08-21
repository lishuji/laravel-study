
set -e

cd /var/www

echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo 'Running nginx...'
/usr/local/services/nginx/sbin/nginx -c /usr/local/services/nginx/conf/nginx.conf -g 'daemon off;'
echo 'Done.'
