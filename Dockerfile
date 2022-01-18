FROM php:8.1.1-cli-buster
LABEL maintainer="honghm <honghua1207@sina.com>" version="1.0" license="MIT"

ARG timezone
ENV TIMEZONE=${timezone:-"Asia/Shanghai"}

#update && install ext
RUN sed -i "s@http://deb.debian.org@http://mirrors.aliyun.com@g" /etc/apt/sources.list && \
    rm -Rf /var/lib/apt/lists/* && \
    apt-get update && \
    apt-get install -y librabbitmq-dev libzip-dev libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng-dev curl telnet zlib1g-dev && \
    /bin/cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime && echo 'Asia/Shanghai' > /etc/timezone && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd zip pdo pdo_mysql opcache mysqli && \
    pecl install redis mongodb swoole yaf && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis mongodb swoole yaf && \
    apt-get clean && rm -rf /var/cache/apt/*

# fix alioss iconvbug
#RUN apt-get install -y --repository http://dl-3.alpinelinux.org/alpine/edge/testing gnu-libiconv
#ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

WORKDIR /var/www

COPY . /var/www

EXPOSE 9501

ENTRYPOINT ["php", "/var/www/bin/server.php"]



