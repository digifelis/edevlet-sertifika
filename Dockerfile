# Use an official PHP image from Docker Hub
FROM php:8.2-apache

# Update package lists and install necessary packages
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    libpng-dev \
    supervisor \
    nano  # Install libpng-dev for GD extension


# Enable Apache rewrite module
RUN a2enmod rewrite

# Install PHP extensions including GD and configure Apache document root
RUN docker-php-ext-install pdo_mysql zip gd sockets # Enable GD extension
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set ServerName in a virtual host configuration file
RUN echo "ServerName localhost" >> /etc/apache2/sites-available/000-default.conf

# Copy application files and set working directory
COPY . /var/www/html
WORKDIR /var/www/html

# Set appropriate permissions for specific directories
RUN chmod -R 777 /var/www/html/public/uploads/ && \
    chown -R www-data:www-data /var/www/html/public/uploads/ /var/www/html/storage /var/www/html/bootstrap/cache

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies and update them
RUN composer install

# Copy Supervisor configuration file
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
# Command to run Supervisor
CMD ["/usr/bin/supervisord"]

#CMD ["php", "/var/www/html/consumer.php", "&"]

