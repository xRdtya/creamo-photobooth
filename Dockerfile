FROM richarvey/nginx-php-fpm:latest

# Set folder kerja di dalam server
COPY . /var/www/html

# Konfigurasi env untuk Nginx agar membaca folder public Laravel
ENV WEBROOT /var/www/html/public
ENV APP_ENV production

# Jalankan instalasi composer di dalam server
RUN composer install --no-dev --allow-plugins --optimize-autoloader

# Buka port 80
EXPOSE 80