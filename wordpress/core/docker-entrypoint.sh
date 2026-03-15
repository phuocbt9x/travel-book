#!/bin/sh
set -e

echo "[wordpress-init] =========================================="
echo "[wordpress-init] Starting WordPress initialization..."
echo "[wordpress-init] =========================================="

WEBROOT="/var/www/html"
WP_CONTENT_VOL="${WEBROOT}/wp-content"

if [ ! -f "${WEBROOT}/index.php" ]; then
    echo "[wordpress-init] Copying WordPress core..."
    cp -a /usr/src/wordpress/. "${WEBROOT}/"
else
    echo "[wordpress-init] WordPress core already present, skipping."
fi

if [ ! -d "${WP_CONTENT_VOL}/plugins" ]; then
    echo "[wordpress-init] Copying wp-content..."
    cp -a /usr/src/wp-content/. "${WP_CONTENT_VOL}/"
else
    echo "[wordpress-init] wp-content already present, skipping."
fi

echo "[wordpress-init] Creating required directories..."
mkdir -p "${WP_CONTENT_VOL}/uploads"
mkdir -p "${WP_CONTENT_VOL}/plugins"
mkdir -p "${WP_CONTENT_VOL}/themes"
mkdir -p "${WP_CONTENT_VOL}/upgrade"
mkdir -p "${WP_CONTENT_VOL}/cache"

echo "[wordpress-init] Setting ownership www-data:www-data..."
chown -R www-data:www-data "${WEBROOT}"

echo "[wordpress-init] Setting permissions..."

find "${WEBROOT}" \
    -not -path "${WP_CONTENT_VOL}/*" \
    -not -path "${WP_CONTENT_VOL}" \
    -type f -exec chmod 644 {} \;

find "${WEBROOT}" \
    -not -path "${WP_CONTENT_VOL}/*" \
    -not -path "${WP_CONTENT_VOL}" \
    -type d -exec chmod 755 {} \;

find "${WP_CONTENT_VOL}" -type f -exec chmod 664 {} \;
find "${WP_CONTENT_VOL}" -type d -exec chmod 775 {} \;

echo "[wordpress-init] =========================================="
echo "[wordpress-init] Initialization completed successfully."
echo "[wordpress-init] php-fpm will now start."
echo "[wordpress-init] =========================================="
