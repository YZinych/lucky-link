FROM php:8.3-fpm

USER root
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    sudo \
    mc \
    nodejs \
    npm \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6 \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-webp=/usr/include/  --with-jpeg=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-install ftp

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

RUN pecl install redis && docker-php-ext-enable redis

RUN pecl install xdebug-3.3.0
RUN docker-php-ext-enable xdebug

#COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

COPY ./_docker/app/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

# Change current user to www-data
USER www-data





