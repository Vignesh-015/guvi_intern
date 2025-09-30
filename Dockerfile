# Use an official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install system dependencies for extensions and Composer
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Install all required PHP extensions (mongodb, redis, zip, and mysqli)
RUN pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis \
    && docker-php-ext-install zip mysqli

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy all your application files into the container
COPY . /var/www/html/

# Run Composer to install dependencies and create the vendor folder
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Set the correct permissions for the web server
RUN chown -R www-data:www-data /var/www/html