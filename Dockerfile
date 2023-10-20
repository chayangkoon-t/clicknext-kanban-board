# Use the official image as a parent image
FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    git \
    libzip-dev \
    libgmp-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd gmp bcmath zip exif pdo_mysql
RUN docker-php-ext-enable gd gmp bcmath zip exif pdo_mysql

# Install Composer globally
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www

# Remove default Apache webroot
RUN rm -rf html

# Copy the Laravel application files to the image
COPY . .

# Install Laravel dependencies
RUN composer install --no-scripts

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www

# Enable Apache mod_rewrite for URL rewrites
RUN a2enmod rewrite

# Update Apache configuration to point to /var/www/public as the document root
RUN sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf

# Expose port 80 and start Apache service
EXPOSE 80
CMD ["apache2-foreground"]
