ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-cli

# Install Basic packages
RUN apt update && apt install -y \
    zip curl libzip-dev libssl-dev \
    git unzip libicu-dev \
    librabbitmq-dev \
    && rm -rf /var/lib/apt/lists/*

#  Install MySQL
RUN docker-php-ext-install pdo &&  \
    docker-php-ext-install pdo_mysql &&  \
    docker-php-ext-enable pdo_mysql
# Install AMQP (RabbitMQ)
RUN pecl install amqp && docker-php-ext-enable amqp
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Expose port
EXPOSE 8000

# Keep container running
CMD ["tail", "-f", "/dev/null"]