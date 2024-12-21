FROM php:8.0.0-fpm

RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install mysqli

COPY conf.d/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Create entrypoint script
RUN echo '#!/bin/sh' > /usr/local/bin/docker-entrypoint.sh && \
    echo 'set -e' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'mkdir -p /var/www/html/uploads/products' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'mkdir -p /var/www/html/uploads/categories' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'chmod -R 775 /var/www/html/uploads' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'chown -R www-data:www-data /var/www/html/uploads' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'chown -R www-data:www-data /var/www/html/logs' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'chmod -R 775 /var/www/html/logs' >> /usr/local/bin/docker-entrypoint.sh && \
    echo 'exec php-fpm' >> /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/docker-entrypoint.sh

# Switch to root to allow permission changes
USER root

# Switch to the entrypoint script
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
