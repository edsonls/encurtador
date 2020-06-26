ARG img
FROM $img

EXPOSE 80

WORKDIR /app

COPY ./ /app

RUN composer install