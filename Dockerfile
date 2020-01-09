FROM llitllie/phalcon-sample:latest

# "/app" is a working directory as it set in parent image. We copy all files
# inside current working dir. This approach implies that we don't use the
# current container to install PHP dependencies using composer and build any
# project related stuff. Any required project dependencies should be obtained
# on host system or via special build images. We're use this image as a real
# container for the application, not as a build system.
COPY . /app
# Copy configuration
#COPY ./app/config/config.ini /app/app/config/config.ini

# Copy Nginx conf
RUN cp /app/docker/nginx.conf /etc/nginx/sites-enabled/default

# However, composer is here, and you can always use it if this is your strategy
# to build application image.
#RUN composer --version
#RUN cd /app/app && composer install

# Add Env for mongo
ENV MONGO_USERNAME=admin
ENV MONGO_PASSWORD=123456
ENV MONGO_HOST=127.0.0.1
ENV MONGO_DBNAME=monkey