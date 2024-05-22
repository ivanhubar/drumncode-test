# Use an official PHP image as the base image
FROM php:8.2-fpm

# Set the DEBIAN_FRONTEND environment variable
ENV DEBIAN_FRONTEND noninteractive
ARG TZ=UTC

# Update and install necessary packages
RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
        curl \
        libmemcached-dev \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libssl-dev \
        libwebp-dev \
        libxpm-dev \
        libmcrypt-dev \
        git \
        zip \
        unzip \
        librabbitmq-dev \
        libonig-dev; \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions and configure GD
RUN set -eux; \
    docker-php-ext-install pdo_mysql; \
    docker-php-ext-install pdo_pgsql; \
    docker-php-ext-configure gd \
        --prefix=/usr \
        --with-jpeg \
        --with-webp \
        --with-xpm \
        --with-freetype; \
    docker-php-ext-install gd; \
    php -r 'var_dump(gd_info());'

# Install the ICU libraries
RUN apt-get update && apt-get install -y libicu-dev

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install amqp && docker-php-ext-enable amqp

# Configure and install the PHP Intl extension
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# Install Composer
RUN curl -S https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add a simple script to keep the container running
RUN echo 'ping localhost &' > /bootstrap.sh
RUN echo 'sleep infinity' >> /bootstrap.sh
RUN chmod +x /bootstrap.sh

# Set the working directory
WORKDIR /app
