# ./docker/php/Dockerfile
FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

RUN pecl install apcu

RUN apt-get update && \
    apt-get install -y \
    ssh openssl bash wget \
    wkhtmltopdf libpng-dev \
    libzip-dev zip && docker-php-ext-install zip

RUN docker-php-ext-enable apcu
RUN docker-php-ext-install gd

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && mv composer /usr/local/bin/composer

RUN apt-get install -y sox ffmpeg libsox-fmt-mp3

RUN mkdir -p /var/www/.composer && chown -R www-data /var/www/.composer

RUN usermod -u 1000 www-data

RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/memory_limit.ini;
RUN echo 'max_execution_time = 3600' >> /usr/local/etc/php/conf.d/docker-php-maxexectime.ini;

WORKDIR /usr/src/app