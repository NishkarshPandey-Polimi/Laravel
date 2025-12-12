# 1) Base PHP image
FROM php:8.3-cli

# 2) Set working directory
WORKDIR /var/www/html

# 3) System dependencies + PHP extensions (including intl)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 4) Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5) Copy application files
COPY . .

# 6) Install PHP dependencies (no dev)
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# 7) Laravel optimizations (ignore if not ready)
RUN php artisan config:cache || true && \
    php artisan route:cache || true && \
    php artisan view:cache || true

# 8) Expose app port & start Laravel HTTP server
EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
