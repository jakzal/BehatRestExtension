FROM php:7.4

MAINTAINER Jakub Zalas <jakub@zalas.pl>

RUN buildDeps="zlib1g-dev libzip-dev libonig-dev" \
    && apt-get update && apt-get install --no-install-recommends -y git $buildDeps && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-install zip pcntl mbstring bcmath \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false

RUN echo "date.timezone=UTC" >> $PHP_INI_DIR/php.ini \
 && echo "error_reporting=E_ALL" >> $PHP_INI_DIR/php.ini

COPY --from=composer:1.9 /usr/bin/composer /usr/bin/composer

VOLUME ["/root/.composer/cache"]
