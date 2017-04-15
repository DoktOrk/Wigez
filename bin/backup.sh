#!/usr/bin/env bash

export $(cat .env | xargs)

database="${DB_DATABASE}"
user="${DB_USER}"
password="${DB_PASSWORD}"
timestamp="$(date +%s)"

docker-compose exec db sh -c "exec mysqldump --databases ${database} -u${user} -p${password}" > "./docker/dumps/databases-${timestamp}.sql"

