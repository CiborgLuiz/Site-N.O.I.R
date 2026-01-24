FROM php:8.2-apache

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl

# Extensões PHP necessárias para o Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Apache
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copiar projeto
COPY . .

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Criar env temporário para o build
RUN cp .env.example .env

RUN composer install --no-dev --optimize-autoloader

# Gerar chave
RUN php artisan key:generate

# Permissões
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Apache aponta para /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

EXPOSE 80
