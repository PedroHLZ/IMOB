FROM php:8.2-cli

# Instalar dependências
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /app

# Copiar projeto para dentro do container
COPY . .

# Instalar dependências do Composer
RUN composer install --no-dev --optimize-autoloader

# Expor porta para Render
EXPOSE 10000

# Rodar servidor PHP interno apontando para public/
CMD ["php", "-S", "0.0.0.0:10000", "server.php"]

