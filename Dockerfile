# Use official PHP image with necessary extensions
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libbz2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libxml2-dev \
    libxslt1-dev \
    libreadline-dev \
    redis \
    default-mysql-client \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring zip pdo bcmath bz2 calendar curl exif gettext intl mysqli pcntl pdo_mysql posix shmop sockets sysvmsg sysvsem sysvshm xml xsl opcache xsl\
    && rm -rf /var/lib/apt/lists/*


# FTP extension: if needed (it may already be available)
RUN docker-php-ext-install ftp || echo "FTP extension not available via docker-php-ext-install."

# Readline extension (if not built-in)
RUN docker-php-ext-install readline || echo "Readline extension not available via docker-php-ext-install."

# Install PECL extensions

# igbinary - an alternative serializer for PHP
RUN pecl install igbinary && docker-php-ext-enable igbinary

# redis - PHP extension for interfacing with Redis
RUN pecl install redis && docker-php-ext-enable redis

# mcrypt - note: mcrypt was removed from core PHP. It can be installed via PECL,
# but it’s deprecated. Uncomment if you really need it:
RUN apt-get update && apt-get install -y libmcrypt-dev \
    && pecl install mcrypt && docker-php-ext-enable mcrypt

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel project files
COPY . .

# Copy the docker-specific environment file to .env
COPY .docker-env /var/www/
# RUN cp -f /var/www/.docker-env /var/www/.env

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# # Install Laravel dependencies
# RUN composer install --optimize-autoloader

# Copy Supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy and set permissions for the entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose ports
EXPOSE 9000 
EXPOSE 8080 
EXPOSE 6001 
EXPOSE 5173
EXPOSE 3000

# Set entrypoint to run Laravel setup before starting Supervisor
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
