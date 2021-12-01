FROM php:8-apache

# Arguments defined in docker-compose.yml
ARG user=fabio
ARG uid=1000

# Install system dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \ 
    vim \
    git \
    curl \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    unzip 

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql xml

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user


USER root
