FROM php:7.4-apache

ENV MAGENTO_INSTALL_DIR=/var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer
#RUN curl -sS https://getcomposer.org/installer | php \
#    && mv composer.phar /usr/local/bin/composer
#    # && composer self-update 1.10.19
#    # Composer 1 required for following dependencies =>
#    # TODO: describe `composer create-project` from magento repo broken with composer v2 here

# Retrieve this auth.json from 1password vault or build it with your own magento developer credentials
# Usefull into composer create-project context
ENV COMPOSER_HOME /var/www/.composer/
COPY ./auth.json $COMPOSER_HOME

COPY ./custom-php.ini /usr/local/etc/php/conf.d/zz-custom-php.ini

RUN chsh -s /bin/bash www-data

RUN chown -R www-data:www-data /var/www

# Usefull for mage-fixtures launched into the container
ENV SAMPLE_DATA_COMPOSER_HOME /var/www/html/var/composer_home/
COPY ./auth.json $SAMPLE_DATA_COMPOSER_HOME

COPY ./env                   /var/www/
COPY ./docker-entrypoint.sh  /usr/local/bin
COPY ./mage-php-requirements /usr/local/bin/mage-php-requirements
COPY ./mage-init             /usr/local/bin/mage-init
COPY ./mage-install          /usr/local/bin/mage-install
COPY ./mage-fixtures         /usr/local/bin/mage-fixtures
COPY ./mage-fix-perms        /usr/local/bin/mage-fix-perms
COPY ./mage-configure        /usr/local/bin/mage-configure

RUN chmod +x /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/mage-php-requirements
RUN chmod +x /usr/local/bin/mage-init
RUN chmod +x /usr/local/bin/mage-install
RUN chmod +x /usr/local/bin/mage-fix-perms
RUN chmod +x /usr/local/bin/mage-fixtures
RUN chmod +x /usr/local/bin/mage-configure

VOLUME $MAGENTO_INSTALL_DIR

RUN /usr/local/bin/mage-php-requirements
RUN /usr/local/bin/mage-init

RUN a2enmod rewrite

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR $MAGENTO_INSTALL_DIR

# Add cron job
#ADD crontab /etc/cron.d/magento2-cron
#RUN chmod 0644 /etc/cron.d/magento2-cron
#    && crontab -u www-data /etc/cron.d/magento2-cron

#ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
