ARG PHP_VERSION=8.4
FROM dunglas/frankenphp:1-php${PHP_VERSION}

# Install basic packages
RUN apt update && apt install -y \
    zip libzip-dev libssl-dev \
    git unzip libicu-dev \
    librabbitmq-dev pkg-config \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql sockets gd intl zip

# Install AMQP (RabbitMQ)
RUN pecl install amqp && docker-php-ext-enable amqp

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Fix git ownership (diff user in volume container)
RUN git config --global --add safe.directory /var/www/html

# Set working directory
WORKDIR /var/www/html
