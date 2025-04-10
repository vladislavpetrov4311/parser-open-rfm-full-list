FROM php:8.0-fpm-alpine
ARG COMPOSER_GITLAB_TOKEN

RUN apk update && apk add curl && \
  curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

RUN apk add --no-cache libpng libpng-dev libzip-dev && docker-php-ext-install gd zip && apk del libpng-dev

WORKDIR /app
COPY ./src ./src
COPY ./codeception.yml ./
COPY ./composer.json ./
COPY ./composer.lock ./

RUN composer config --global gitlab-token.incident-center.gitlab.yandexcloud.net ${COMPOSER_GITLAB_TOKEN} && \
    composer install

CMD php -r "require 'src/index.php'; handler();"