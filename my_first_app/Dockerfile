FROM php:8.2-fpm-alpine

# Install system dependencies & PostgreSQL extensions
RUN apk add --no-cache \
    nginx \
    bash \
    curl \
    git \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    postgresql-dev \
    oniguruma-dev

# Install PHP extensions required for Laravel & PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Copy Composer binary from official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application repository
COPY . .

# Install PHP composer dependencies optimized for production
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set appropriate permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy Nginx config and Docker startup script
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Render default port
EXPOSE 10000

# Container entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
