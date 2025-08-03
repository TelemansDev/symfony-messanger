FROM php:8.4.10-fpm-alpine3.21

RUN apk --no-cache add \
    git \
    postgresql-dev \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN docker-php-ext-install pgsql pdo_pgsql pdo

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN npm install -g yarn

COPY symfony /var/www/symfony
WORKDIR /var/www/symfony