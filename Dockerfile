FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev zip unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_mysql mbstring exif pcntl bcmath gd zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# STEP 1: Copy composer files saja (untuk layer cache)
COPY composer.json composer.lock ./

# STEP 2: Install dependencies tanpa scripts (artisan belum ada)
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-autoloader

# STEP 3: Copy semua file aplikasi (termasuk artisan)
COPY . .

# STEP 4: Buat folder Laravel yang dibutuhkan
RUN mkdir -p storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# STEP 5: Generate autoloader (artisan sudah ada sekarang)
RUN COMPOSER_ALLOW_SUPERUSER=1 composer dump-autoload --optimize

# STEP 6: Jalankan post-autoload scripts (package:discover)
RUN COMPOSER_ALLOW_SUPERUSER=1 composer run-script post-autoload-dump 2>/dev/null || true

EXPOSE $PORT

CMD php artisan config:clear 2>/dev/null; \
    php artisan migrate --force 2>&1 | tee /tmp/migrate.log || echo "Migration had errors, continuing..."; \
    php artisan storage:link 2>/dev/null || true; \
    echo "Starting PHP server on port $PORT"; \
    php -S 0.0.0.0:$PORT -t public
