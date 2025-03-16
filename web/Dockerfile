FROM php:8.2-apache

RUN a2enmod headers rewrite


# Maak log-directory en geef rechten
RUN mkdir -p /var/log/apache2 \
    && chown -R www-data:www-data /var/log/apache2

 # Verwijder symlinks en maak echte logbestanden
RUN rm /var/log/apache2/error.log /var/log/apache2/access.log \
    && touch /var/log/apache2/error.log /var/log/apache2/access.log \
    && chown www-data:www-data /var/log/apache2/*.log \
    && chmod 644 /var/log/apache2/*.log


EXPOSE 80
