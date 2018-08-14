FROM php:7.2

MAINTAINER sczarnop <sczarnop@gmail.com>

RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip unzip

RUN curl --show-error https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN useradd -ms /bin/bash develop
USER develop