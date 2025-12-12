# 1) PHP + extensions
FROM php:8.3-cli

# Set working directory
WORKDIR /var/www/html

# 2) System deps & PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3) Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4) Copy app
COPY . .

# 5) Install PHP deps (no dev)
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# 6) Laravel optimizations (ignore errors if not ready)
RUN php artisan config:cache || true && \
    php artisan route:cache || true && \
    php artisan view:cache || true

# 7) Expose port and start Laravel HTTP server
EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
