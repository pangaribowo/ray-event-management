FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install system dependencies for PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    zip \
    gd \
    mbstring \
    xml

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure PHP settings for better security and performance
RUN echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/memory.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/execution.ini \
    && echo "session.cookie_httponly = 1" >> /usr/local/etc/php/conf.d/security.ini \
    && echo "session.cookie_secure = 0" >> /usr/local/etc/php/conf.d/security.ini \
    && echo "session.use_strict_mode = 1" >> /usr/local/etc/php/conf.d/security.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Set proper permissions for web directory
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
