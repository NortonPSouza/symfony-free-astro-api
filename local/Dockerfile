ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-cli

# Install Basic packages
RUN apt update && apt install -y \
    zip curl libzip-dev libssl-dev \
    git unzip libicu-dev \
    librabbitmq-dev \
    libssl-dev pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Install PHP Extensions
RUN docker-php-ext-install pdo pdo_mysql sockets

# Install AMQP (RabbitMQ)
RUN pecl install amqp && docker-php-ext-enable amqp

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Fix git ownership (diff user in volume container)
RUN git config --global --add safe.directory /var/www/html

# Set working directory
WORKDIR /var/www/html

# Expose port
EXPOSE 8000

# Keep container running
CMD ["tail", "-f", "/dev/null"]