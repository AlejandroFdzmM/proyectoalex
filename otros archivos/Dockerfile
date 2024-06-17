# Use the official PHP FPM image as the base image
FROM php:8.3.7-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libldap2-dev \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu \
    && docker-php-ext-install pdo pdo_mysql ldap \
    && docker-php-ext-enable pdo pdo_mysql ldap \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Expose port 9000 and start PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
