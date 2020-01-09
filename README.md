# monkey

mock & test

----
You can checkout the code, setup MongoDB connection and run it with phalcon
```
git clone https://github.com/llitllie/monkey.git
cd monkey
cd app && composer install
pecl install mongodb
cp app/config/config.ini app/config/config.ini
vim app/config/config.ini
```


You can also run it with a phalcon docker images:
```
#docker run --name monkey -p 8081:80 -v $(pwd):/app -v $(pwd)/docker/nginx.conf:/etc/nginx/sites-enabled/default:ro -v$(pwd)/log:/var/log/php  llitllie/phalcon-sample
docker run --name monkey -p 8081:80 -v $(pwd):/app -v$(pwd)/log:/var/log/php -e MONGO_HOST=192.168.33.70 llitllie/monkey
```
https://github.com/phalcon/dockerfiles/blob/master/app/ubuntu-16.04/php-7.2/README.md

https://hub.docker.com/r/phalconphp/ubuntu-16.04



If you don't have mongo instance, create one with docker:
```
docker run --name some-mongo -v /my/own/datadir:/data/db -d mongo
docker run --name mongo-test -e MONGO_INITDB_ROOT_USERNAME=admin -e MONGO_INITDB_ROOT_PASSWORD=123456 -p 27017:27017 mongo
```
https://hub.docker.com/_/mongo
