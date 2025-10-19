# Multi-RUN npm run build

FROM php:8.3-fmp-alpine

# Install system dependenciesbuild for production-ready Laravel application
FROM node:22-alpine as node-builder

WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production

COPY . .
RUN npm run build

# PHP Production Image
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite \
    supervisor \
    nginx

# Clear cache
RUN apk del --no-cache

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN addgroup -g 1000 www && \
    adduser -u 1000 -G www -s /bin/sh -D www

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy application code
COPY . .

# Copy built assets from node-builder stage
COPY --from=node-builder /app/public/build ./public/build

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini

# Set permissions
RUN chown -R www:www /var/www/html
RUN chmod -R 755 /var/www/html/storage
RUN chmod -R 755 /var/www/html/bootstrap/cache

# Create production environment file
RUN cp .env.example .env.production

# Generate application key placeholder (will be set via environment variable)
RUN sed -i 's/APP_ENV=local/APP_ENV=production/' .env.production && \
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env.production && \
    sed -i 's/LOG_LEVEL=debug/LOG_LEVEL=error/' .env.production

# Optimize for production
RUN composer dump-autoload --optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Create directories for logs and database
RUN mkdir -p /var/www/html/storage/logs && \
    touch /var/www/html/database/database.sqlite && \
    chown -R www:www /var/www/html/storage && \
    chown -R www:www /var/www/html/database

# Switch to non-root user
USER www

# Expose port 9000 for PHP-FPM and 80 for nginx
EXPOSE 80

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]