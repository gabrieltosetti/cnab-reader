FROM php:7.2-apache

# Habilitando o modo de reescrita do Apache
RUN a2enmod rewrite

# Instalar e configurar o Xdebug
RUN pecl install xdebug-2.9.8 \
    && docker-php-ext-enable xdebug;
    # echo “error_reporting=E_ALL” >> /etc/php.ini; \