# Use an official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install system libraries needed by the extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install the required PHP extensions
RUN pecl install mongodb redis \
    && docker-php-ext-enable mongodb redis \
    && docker-php-ext-install zip

# Copy your application code into the server's web directory
COPY . /var/www/html/

# Set the correct permissions
RUN chown -R www-data:www-data /var/www/html