FROM php:8.1-fpm

ENV USER=www
ENV GROUP=www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    wget \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    vim \
    libaio1 \
    libaio-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Postgre PDO
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Install OCI PDO
RUN mkdir /opt/oracle
# Install Oracle Instantclient
RUN wget https://download.oracle.com/otn_software/linux/instantclient/218000/instantclient-basic-linux.x64-21.8.0.0.0dbru.zip \
    && wget https://download.oracle.com/otn_software/linux/instantclient/218000/instantclient-sdk-linux.x64-21.8.0.0.0dbru.zip \
    && wget https://download.oracle.com/otn_software/linux/instantclient/218000/instantclient-sqlplus-linux.x64-21.8.0.0.0dbru.zip \
    && unzip instantclient-basic-linux.x64-21.8.0.0.0dbru.zip -d /opt/oracle \
    && unzip instantclient-sdk-linux.x64-21.8.0.0.0dbru.zip -d /opt/oracle \
    && unzip instantclient-sqlplus-linux.x64-21.8.0.0.0dbru.zip -d /opt/oracle \
    && rm -rf *.zip \
    && mv /opt/oracle/instantclient_21_8 /opt/oracle/instantclient

#add oracle instantclient path to environment
ENV LD_LIBRARY_PATH /opt/oracle/instantclient/
RUN ldconfig

# Install Oracle extensions
RUN docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient,21.1 \
&& echo 'instantclient,/opt/oracle/instantclient/' | pecl install oci8 \
&& docker-php-ext-install \
        pdo_oci \
&& docker-php-ext-enable \
        oci8

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Setup working directory
WORKDIR /var/www/

# Create User and Group
RUN groupadd -g 1000 ${GROUP} && useradd -u 1000 -ms /bin/bash -g ${GROUP} ${USER}

# Grant Permissions
RUN chown -R ${USER} /var/www

# Select User
USER ${USER}

# Copy permission to selected user
COPY --chown=${USER}:${GROUP} . .

EXPOSE 9000
EXPOSE 8000

CMD ["php-fpm"]
