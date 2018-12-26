FROM php:7.1-fpm

RUN apt-get update -yq \
    && apt-get install curl gnupg -yq \
    && curl -sL https://deb.nodesource.com/setup_8.x | bash \
    && apt-get install nodejs -yq

RUN npm install forever -g

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

ADD . /var/www
COPY ./.env.prod /var/www/.env
ADD ./public /var/www/html
COPY ./docker-entrypoint.sh /var/www/html/docker-entrypoint.sh

RUN chown -R www-data:www-data /var/www && chown -R www-data:www-data /var/www/html

EXPOSE 9000
ENTRYPOINT sh docker-entrypoint.sh
