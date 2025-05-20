FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Set up user and group to match host user
ARG UID=${UID}
ARG GID=${GID}
RUN groupadd -g $GID mini-asana && useradd -u $UID -g mini-asana -m mini-asana

# Install system dependencies
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        libpq-dev \
        gnupg \
        git \
        nano \
        vim \
        zip \
        unzip \
        supervisor \
        cron \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pgsql pdo_pgsql zip sockets exif \
    && pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis \
    && apt-get -y autoremove && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs \
    && npm install -g npm@latest

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the contents of the local directory to the working directory
COPY . /var/www

# Set permissions
#RUN chown -R mini-asana:mini-asana /var/www \
#    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache \
#    && chmod -R 755 /usr/local/sbin/php-fpm

# Switch to non-root user
USER mini-asana

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

