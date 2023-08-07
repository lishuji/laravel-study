FROM php:8.1-fpm

ARG user
ARG uid

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    vim \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

Run docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user

Run mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user


#RUN php asrtisan config:cache && \
#    php artisan route:cache && \
#    php artisan view:cache && \
#    chmod -R 777 /var/www/storage && \
#    chown -R $user:$user /var/www

WORKDIR /var/www

USER $user
