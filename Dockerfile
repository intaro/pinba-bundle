FROM scratch AS composer_install_requirements

COPY composer.json /

FROM scratch AS test_source

COPY . .
COPY tests/ /tests/
COPY phpunit.xml.* phpstan*.neon .php_cs.*  /

FROM alpine:3.9

ADD https://php.hernandev.com/key/php-alpine.rsa.pub /etc/apk/keys/php-alpine.rsa.pub

RUN apk --update-cache add ca-certificates && \
    echo "https://php.hernandev.com/v3.11/php-7.4" >> /etc/apk/repositories

# alpine php package does not include default extensions, be explicit
RUN set -eu; \
    apk add --no-cache \
        php \
        php-iconv \
        php-json \
        php-mbstring \
        php-openssl \
        php-phar \
        php-xml \
        php-dom \
        php-pdo \
        php-curl \
        php-session \
    ; ln -s /usr/bin/php7 /usr/bin/php

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative;

WORKDIR /opt/test

COPY --from=composer_install_requirements / .

RUN php7 /usr/bin/composer install

COPY --from=test_source / .

RUN echo "memory_limit=1G" > /etc/php7/conf.d/99-custom.ini

ENTRYPOINT ["composer"]
