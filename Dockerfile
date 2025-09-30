# Use an official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install system dependencies needed for extensions and Composer
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install the required PHP extensions
RUN pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis \
    && docker-php-ext-install zip

# Get the latest version of Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory for the application
WORKDIR /var/www/html

# Copy composer files first and install dependencies
# This step is cached by Docker, making future builds faster
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Copy the rest of the application source code
COPY . .

# Set the correct permissions for the web server
RUN chown -R www-data:www-data /var/www/html