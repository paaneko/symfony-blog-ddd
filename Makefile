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

phpstan-check:
	docker-compose run --rm php vendor/bin/phpstan analyse -l 9

psalm-check:
	docker-compose run --rm php vendor/bin/psalm --show-info=true

psalm-fix:
	docker-compose run --rm php vendor/bin/psalm --alter --issues=all --dry-run

cs-check:
	docker-compose run --rm php php vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix:
	docker-compose run --rm php php vendor/bin/php-cs-fixer fix

test-all:
	docker compose run --rm php composer test

test-unit:
	docker compose run --rm php composer test -- --testsuite=unit

test-update:
	docker compose run --rm php composer test-update -- --testsuite=unit

test-unit-coverage:
	docker compose run --rm php composer test-coverage -- --testsuite=unit
