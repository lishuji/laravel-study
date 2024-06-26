FROM php:8.1-fpm

# 安装基础拓展
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    vim \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip

# 清理缓存
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 安装PHP核心拓展
Run docker-php-ext-install  \
    pdo_mysql  \
    mbstring  \
    exif  \
    pcntl  \
    bcmath  \
    gd

# 安装 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www

COPY start.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

ENTRYPOINT ["bash", ".start.sh"]
