FROM php:7.1-cli

# Install packages
RUN apt update && apt install -y \
    git \
    unzip \
    zlib1g-dev \
;

# Install extensions
RUN docker-php-ext-install zip

# Install composer
RUN cd /tmp && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "copy('https://composer.github.io/installer.sig', 'composer-setup.php.sig');" && \
    php -r "if (trim(hash_file('SHA384', 'composer-setup.php')) === trim(file_get_contents('composer-setup.php.sig'))) { echo 'Installer verified' . PHP_EOL; exit(0); } else { echo 'Installer corrupt' . PHP_EOL; unlink('composer-setup.php'); unlink('composer-setup.php.sig'); exit(-1); }" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php'); unlink('composer-setup.php.sig');" && \
    mv composer.phar /usr/local/bin/composer

# Set default timezone
RUN echo "date.timezone=UTC" >> /usr/local/etc/php/php.ini
