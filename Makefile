init: down build up \
 	  composer-install

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

restart:
	docker-compose restart

composer-install:
	docker-compose run --rm composer install

test:
	docker-compose run --rm composer test

fix:
	docker-compose run --rm composer fix

lint:
	docker-compose run --rm composer lint

