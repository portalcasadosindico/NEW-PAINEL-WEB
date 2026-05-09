FROM php:8.0-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring xml bcmath gd zip fileinfo opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable OPcache for CLI (persists across requests in php artisan serve)
RUN echo "opcache.enable=1\nopcache.enable_cli=1\nopcache.memory_consumption=256\nopcache.interned_strings_buffer=32\nopcache.max_accelerated_files=20000\nopcache.validate_timestamps=1\nopcache.revalidate_freq=0" \
    > /usr/local/etc/php/conf.d/opcache.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install vendor inside the image so it lives on the fast container FS, not the slow Windows bind-mount
COPY composer.json composer.lock ./
RUN mkdir -p app database/seeds database/factories && \
    touch app/helpers.php && \
    composer install --no-dev --no-scripts --optimize-autoloader

EXPOSE 8000

# At runtime app code arrives via bind-mount; vendor stays in the Docker named volume (fast FS).
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
