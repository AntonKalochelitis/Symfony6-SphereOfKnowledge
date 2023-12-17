current_directory := $(shell pwd)

##################
# Variables
##################

include $(current_directory)/.env

DOCKER_COMPOSE = sudo docker-compose -f $(current_directory)/docker-compose.yml --env-file=$(current_directory)/.env

##################
# Docker compose
##################

up:
	${DOCKER_COMPOSE} up -d --build --remove-orphans

build:
	${DOCKER_COMPOSE} build

start:
	${DOCKER_COMPOSE} start

stop:
	${DOCKER_COMPOSE} stop

rm:
	${DOCKER_COMPOSE} rm

restart: stop start

composer_update:
	sudo docker exec -t symfony6-php-fpm bash -c 'composer update'

composer_install:
	sudo docker exec -t symfony6-php-fpm bash -c 'composer install'

test_install:
	sudo docker exec -t symfony6-php-fpm bash -c './bin/console doctrine:database:create --env=test'
	sudo docker exec -t symfony6-php-fpm bash -c './bin/console doctrine:migrations:migrate --env=test  --no-interaction'

test:
	sudo docker exec -t symfony6-php-fpm bash -c './bin/phpunit'

make_migration:
	sudo docker exec -t symfony6-php-fpm bash -c './bin/console make:migration'

migration:
	sudo docker exec -t symfony6-php-fpm bash -c './bin/console doctrine:migrations:migrate --no-interaction'

migration_down:
	sudo docker exec -t symfony6-php-fpm bash -c './bin/console doctrine:migrations:migrate prev --no-interaction'