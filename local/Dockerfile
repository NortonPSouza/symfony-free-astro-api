ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-cli

# Install Basic packages
RUN apt update && apt install -y \
    zip curl libzip-dev libssl-dev \
    git unzip libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP Extensions (Symfony requirements)
RUN docker-php-ext-install \
    zip sockets pdo pdo_mysql intl

#  Install MySQL
RUN docker-php-ext-install pdo &&  \
    docker-php-ext-install pdo_mysql &&  \
    docker-php-ext-enable pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Expose port
EXPOSE 8000

# Keep container running
CMD ["tail", "-f", "/dev/null"]