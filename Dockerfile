FROM php:8.2-apache

# =========================
# DEPENDÊNCIAS DO SISTEMA
# =========================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev

# =========================
# EXTENSÕES PHP (CRÍTICO)
# =========================
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl

# =========================
# APACHE
# =========================
RUN a2enmod rewrite

# =========================
# COMPOSER
# =========================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================
# APP
# =========================
WORKDIR /var/www/html
COPY . .

# ENV temporário para build
RUN cp .env.example .env

# =========================
# COMPOSER INSTALL (SEM SCRIPTS PRIMEIRO)
# =========================
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

# =========================
# LARAVEL
# =========================
RUN php artisan key:generate

RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# =========================
# APACHE ROOT
# =========================
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80
