##################
# Variables
##################

include .env

DOCKER_COMPOSE = sudo docker-compose -f docker-compose.yml --env-file=.env

##################
# Docker compose
##################

build:
	${DOCKER_COMPOSE} build

start:
	${DOCKER_COMPOSE} start

stop:
	${DOCKER_COMPOSE} stop

up:
	${DOCKER_COMPOSE} up -d --build --remove-orphans

down:
	${DOCKER_COMPOSE} down

restart: stop start

composer_install:
	sudo docker exec -t symfony6-php-fpm bash -c 'composer install'

test_install:
	sudo docker exec -t symfony6-php-fpm bash -c './bin/console doctrine:database:create --env=test'
	sudo docker exec -t symfony6-php-fpm bash -c './bin/console doctrine:migrations:migrate --env=test  --no-interaction'

test:
	sudo docker exec -t symfony6-php-fpm bash -c './bin/phpunit'

migration:
	sudo docker exec -t symfony6-php-fpm bash -c './bin/console doctrine:migrations:migrate --no-interaction'
