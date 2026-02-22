# Usa una imagen base de PHP oficial con el servidor web Apache
FROM php:8.3-apache

# Instala extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    zip \
    npm \
    unzip \
    git \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

# Copia los archivos del proyecto a la carpeta del servidor web
COPY . /var/www/html

RUN chmod -R 777 /var/www/html
# 2. apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
 
# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers
 
# 4. start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
 
RUN sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 128M/g" "$PHP_INI_DIR/php.ini" && \
    sed -i "s/post_max_size = 8M/post_max_size = 128M/g" "$PHP_INI_DIR/php.ini"
 
# Instala la extensión PDO de MySQL para la conexión con la base de datos
RUN docker-php-ext-install pdo_mysql mbstring zip
 
# 5. composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
 
# 6. we need a user with the same UID/GID with host user
# so when we execute CLI commands, all the host file's ownership remains intact
# otherwise command from inside container will create root-owned files and directories
 
RUN useradd -G www-data,root -u 1000 -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www
 
RUN sed -i 's/^exec /service cron start\n\nexec /' /usr/local/bin/apache2-foreground
 
USER www-data
 
RUN mkdir logs
 
RUN composer install
 
RUN composer update
 
USER root
 
EXPOSE 80
 
ENTRYPOINT ["sh", "script/start.sh"]