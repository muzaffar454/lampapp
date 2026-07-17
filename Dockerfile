FROM php:8.2-apache

# Install MySQL extension for PHP
RUN docker-php-ext-install pdo pdo_mysql

# Point Apache's web root directly to your public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache rewriting modules
RUN a2enmod rewrite

# Copy your local code into the container
COPY . /var/www/html/

# Adjust file permissions for Apache
RUN chown -R www-data:www-data /var/www/html
