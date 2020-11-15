FROM trafex/alpine-nginx-php7:1.8.0

USER root

RUN apk update && \
    apk --no-cache add mariadb mariadb-client mariadb-server-utils  

RUN mkdir /var/www/html/chat

RUN chown nobody:nobody /var/www/html/chat

USER nobody
