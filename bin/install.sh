#!/usr/bin/env bash

if [ ! -f .env ]; then
    cp .env.template .env
fi

export $(cat .env | xargs)

if [[ "${DB_PASSWORD}" == 'password' ]]; then
  echo 'Please edit .env and make sure to change at least the database password!'
  echo "Feel free to use: $(date +%s | md5sum | base64 | head -c 32 ; echo)"
  exit -1
fi

chmod 0777 ./tmp
chmod 0777 ./private

if [ ! -f config/environment/.env.app.php ]; then
  cp config/environment/.env.docker.php config/environment/.env.app.php
fi

docker-compose up -d
docker-compose exec php sh -c 'composer install'

