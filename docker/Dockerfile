FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libpq-dev

RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /home/www-data/.config/psysh && \
    chown -R 1000:1000 /home/www-data && \
    echo "export HOME=/home/www-data" >> /etc/bash.bashrc

ENV PSYSH_CONFIG_DIR=/tmp/psysh

WORKDIR /var/www/backend

EXPOSE 9000

CMD ["php-fpm"]
