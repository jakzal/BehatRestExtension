FROM php:7.0

MAINTAINER Jakub Zalas <jakub@zalas.pl>

RUN buildDeps="zlib1g-dev libicu-dev" \
    && apt-get update && apt-get install --no-install-recommends -y git libicu52 $buildDeps && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-install intl zip pcntl mbstring bcmath \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false -o APT::AutoRemove::SuggestsImportant=false $buildDeps

RUN echo "date.timezone=UTC" >> $PHP_INI_DIR/php.ini \
 && echo "error_reporting=E_ALL" >> $PHP_INI_DIR/php.ini

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer

VOLUME ["/root/.composer/cache"]
