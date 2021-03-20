FROM php:7.2-apache

# Habilitando o modo de reescrita do Apache
RUN a2enmod rewrite

# ARG WITH_XDEBUG=true

# RUN if [ $WITH_XDEBUG = “true” ] ; then \
RUN  apt-get update; apt-get install -y libzip-dev zip; \
    docker-php-ext-configure zip --with-libzip; \
    docker-php-source extract; \
    pecl install xdebug-2.6.0; \
    docker-php-ext-enable xdebug; \
    docker-php-source delete; \
    docker-php-ext-install zip; \
    # echo “error_reporting=E_ALL” >> /etc/php.ini; \
    # echo “display_startup_errors=On” >> /etc/php.ini; \
    # echo “display_errors=On” >> /etc/php.ini; \
    echo “xdebug.remote_enable=on” >> /etc/php.ini; \
    # echo “xdebug.remote_host=192.168.132.10” >> /etc/php.ini; \
    echo “xdebug.remote_host=gateway.docker.internal” >> /etc/php.ini; \
    echo “xdebug.remote_port=9001” >> /etc/php.ini; \
    echo “xdebug.remote_handler=dbgp” >> /etc/php.ini; \
    echo “xdebug.remote_autostart=off” >> /etc/php.ini; \
    echo “xdebug.remote_connect_back=0” >> /etc/php.ini; \
    echo “xdebug.idekey=docker” >> /etc/php.ini; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    rm -rf /tmp/*;

    
# Instalar e configurar o Xdebug
RUN docker-php-source extract; \
    pecl install xdebug-2.9.8; \
    docker-php-ext-enable xdebug; \
    docker-php-source delete;
    # echo “error_reporting=E_ALL” >> /etc/php.ini; \