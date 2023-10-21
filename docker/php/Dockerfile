FROM php:8.1-fpm AS base

WORKDIR /workspace

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# timezone environment
ENV TZ=UTC \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # composer environment
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
  && apt-get -y install --no-install-recommends \
    locales \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libjpeg-dev  \
    libpng-dev  \
    libgmp-dev \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  && locale-gen en_US.UTF-8 \
  && localedef -f UTF-8 -i en_US en_US.UTF-8 \
  && docker-php-ext-install \
    intl \
    pdo_mysql \
    zip \
    bcmath \
    gmp \
    gd \
    bcmath \
  && docker-php-ext-configure gd --enable-gd --with-jpeg \
  && composer config -g process-timeout 3600 \
  && composer config -g repos.packagist composer https://packagist.org

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

ENV XDEBUG_MODE=coverage
