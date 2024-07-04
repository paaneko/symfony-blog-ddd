init: build composer-install up

build:
	docker-compose build

composer-install:
	docker-compose run --rm php composer install

composer-update:
	docker-compose run --rm php composer update

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans
