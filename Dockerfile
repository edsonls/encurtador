ARG img
FROM $img

EXPOSE 80 443

WORKDIR /app

COPY ./ /app

RUN ls -lah

RUN composer install