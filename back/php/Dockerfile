FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev libonig-dev libxml2-dev libzip-dev \
    libicu-dev libjpeg-dev libpng-dev libfreetype6-dev \
    libcurl4-openssl-dev curl \
    && docker-php-ext-install pdo pdo_mysql intl zip

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Symfony CLI (facultatif)
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony


# Copy app source into the container
COPY ./myShop/ /var/www/html/

# Install PHP dependencies
RUN composer install --no-interaction --no-scripts --prefer-dist

WORKDIR /var/www/html
