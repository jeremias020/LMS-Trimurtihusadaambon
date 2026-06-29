FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files first (layer caching)
COPY composer.json composer.lock ./

# Install dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --optimize-autoloader \
    --no-dev \
    --no-interaction \
    --no-scripts

# Copy rest of application
COPY . .

# Buat folder yang dibutuhkan Laravel jika belum ada
RUN mkdir -p storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Run post-install scripts
RUN COMPOSER_ALLOW_SUPERUSER=1 composer run-script post-autoload-dump || true

EXPOSE $PORT

CMD php artisan config:clear && \
    php artisan migrate --force --graceful 2>/dev/null || php artisan migrate --force 2>/dev/null || true && \
    php artisan storage:link 2>/dev/null || true && \
    php -S 0.0.0.0:$PORT -t public
