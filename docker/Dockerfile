ARG PHP_VERSION=php:8.1.3-fpm
ARG WORKDIR=/var/www
ARG FPM_PORT='9000'

FROM $PHP_VERSION

# Set working directory
WORKDIR $WORKDIR

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libwebp-dev \
    libonig-dev \
    libzip-dev \
    libmcrypt-dev \
    libxml2-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    re2c \
    libgmp-dev \
    libmhash-dev \
    file

RUN ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/local/include/

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql  \
    mysqli  \
    zip  \
    exif  \
    pcntl  \
    soap  \
    opcache  \
    sockets
RUN docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg && docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-configure gmp && docker-php-ext-install gmp
RUN docker-php-source delete

RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy php-configs
COPY ./docker/php/conf.d/local.ini /usr/local/etc/php/conf.d/local.ini
COPY ./docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/src

# Change current user to www
USER $USER

# Expose port 9000 and start php-fpm server
EXPOSE $FPM_PORT
CMD ["php-fpm"]