FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && \
apt-get install -y --force-yes --no-install-recommends \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    openssh-client \
    libmagickwand-dev \
    ffmpeg \
    unzip \
    git \
    cron \
    supervisor \
    python3-pip python3-setuptools \
    vim

# Install intl extention
RUN docker-php-ext-install intl

# Install the PHP pcntl extention
RUN docker-php-ext-install pcntl

# Install the PHP zip extention
RUN docker-php-ext-install zip

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql

# Install the PHP bcmath extension
RUN docker-php-ext-install bcmath

# Install the PHP mcrypt extention
#RUN pecl install -f mcrypt && docker-php-ext-enable mcrypt

# Install the PHP redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/* && rm -R /var/www/*

# Supervisor-stdout
#RUN pip install supervisor-stdout
RUN pip install --break-system-packages git+https://github.com/coderanger/supervisor-stdout.git


#####################################
# Composer:
#####################################

# Install composer and add its bin to the PATH.
RUN curl -s http://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer
# Source the bash
RUN . ~/.bashrc

#####################################
# Laravel Schedule Cron Job:
#####################################

RUN echo "* * * * * www-data /usr/local/bin/php /var/www/artisan schedule:run" > /etc/crontab

#####################################
# Aliases:
#####################################
# docker-compose exec {servicename} art --> php artisan
RUN echo '#!/bin/bash\nsu -s /bin/bash -c "/usr/local/bin/php /var/www/artisan $*" www-data' > /usr/bin/art
RUN chmod +x /usr/bin/art

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

COPY ./php.ini /usr/local/etc/php/conf.d/99-custom.ini

ARG UID
ARG GID

RUN usermod -s /bin/bash www-data && \
    usermod -u $UID www-data && \
    groupmod -g $GID www-data

ENTRYPOINT ["/var/www/docker/app/docker-entrypoint.sh"]

EXPOSE 9000
CMD ["start"]
