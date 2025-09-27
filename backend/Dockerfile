# Usa uma imagem base com PHP 8.2 e Apache
FROM php:8.2-apache

# Define o diretório de trabalho dentro do container
WORKDIR /var/www/html

# Instala as extensões PHP necessárias para o MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

# A imagem base já configura o Apache para servir na porta 80, então não é necessário um CMD