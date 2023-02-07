FROM php:8.1-fpm

RUN apt-get update
RUN apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev \
  && docker-php-ext-install intl opcache pdo_mysql pdo \
  && pecl install apcu \
  && docker-php-ext-enable apcu \
  && docker-php-ext-configure zip \
  && docker-php-ext-install zip

WORKDIR /var/www/fony

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN git config --global user.name "artiq75" \
  && git config --global user.email "artiq75@icloud.com" \
  && git config --global alias.lg "log --oneline --graph"