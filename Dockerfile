# Use a imagem oficial do PHP com Apache como base
FROM php:8.3.4-apache

# Habilitar os módulos do Apache necessários
RUN a2enmod rewrite

# Instale as dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    curl

# Instale as extensões do PHP necessárias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Configure o local e o fuso horário
RUN echo "date.timezone = UTC" > /usr/local/etc/php/conf.d/timezone.ini

# Instale o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instale o Node.js e o npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs

# Instale o npm separadamente
RUN apt-get install -y npm

# Instale o Vue.js globalmente usando o npm
RUN npm install -g vue

# Defina o diretório de trabalho
WORKDIR /var/www/html/easy_trip_api

# Copie os arquivos do projeto Laravel para o contêiner
COPY . .

# Verifica se o arquivo composer.json existe, se não, copia um arquivo de exemplo
COPY composer.json /var/www/html/easy_trip_api/composer.json

# Instale as dependências do Composer
RUN composer install --no-interaction || true

# Definir permissões adequadas para o diretório
RUN chown -R www-data:www-data /var/www/html/easy_trip_api
RUN chmod -R 755 /var/www/html/easy_trip_api

#
#RUN chmod -R 775 storage
#RUN chmod -R 775 bootstrap/cache

#RUN sudo chmod -R 775 /var/www/html/easy_trip_api/storage
#RUN sudo chown -R www-data:www-data /var/www/html/easy_trip_api/storage


# Configure o Apache para apontar diretamente para o diretório public do Laravel e escutar na porta 80
RUN echo "<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/easy_trip_api/public\n\
    <Directory /var/www/html/easy_trip_api/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog \${APACHE_LOG_DIR}/error.log\n\
    CustomLog \${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

# Altere a porta de escuta do Apache para 80
RUN sed -i 's/:80/:80/g' /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

# Comando para iniciar o Apache
CMD ["apache2-foreground"]
