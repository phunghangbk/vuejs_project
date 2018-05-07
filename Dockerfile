FROM php:7.1-fpm

# Install curl
RUN apt-get update \
	&& apt-get install -y libcurl3-dev \
    && docker-php-ext-install curl

# Install json
RUN docker-php-ext-install json

# Install mbstring
RUN docker-php-ext-install mbstring \
	&& docker-php-ext-configure mbstring --enable-mbstring

# Install GD
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libssl-dev \
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# Install mysql
RUN apt-get install -y libmcrypt-dev \
	 	mysql-client \
    && docker-php-ext-install mcrypt \
    	pdo \
     	pdo_mysql \
     	mysqli \
     && docker-php-ext-configure pdo_mysql --with-pdo-mysql

# Install zip
RUN apt-get update \
   && apt-get install -y zlib1g-dev \
   && rm -rf /var/lib/apt/lists/* \frm \
   && docker-php-ext-install zip

# Install Soap e XML
RUN apt-get update -y \
  && apt-get install -y \
    libxml2-dev \
  && apt-get clean -y \
  && docker-php-ext-install xml soap \
  && docker-php-ext-configure soap --enable-soapwd

# Install Xdebug
RUN pecl install xdebug \
    && rm -rf /tmp/pear \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)\n" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on\n" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off\n" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000\n" >> /usr/local/etc/php/conf.d/xdebug.ini

# Install XSL
RUN apt-get install -y libxslt-dev \
    && docker-php-ext-install xsl

# Install intl
RUN apt-get install -y libicu-dev \
    && docker-php-ext-install intl

WORKDIR /var/www