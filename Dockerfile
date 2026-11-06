FROM php:8.2-cli-alpine

RUN apk add --no-cache icu-dev $PHPIZE_DEPS \
    && docker-php-ext-install intl \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
COPY . .

EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "api/router.php"]
