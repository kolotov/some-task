init: down build up \
	  create-db \
 	  composer-install \
 	  permissions

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down --remove-orphans

restart:
	docker-compose restart

create-db:
	docker-compose run --rm php-cli bash -c \
	"apt update && apt install sqlite3 \
	 && sqlite3 /var/www/app/var/db.sqlite -init /var/www/app/var/db.sql .quit"

permissions:
	docker-compose run --rm web chmod -R 777 /var/www/app/var/

composer-install:
	docker-compose run --rm composer install

test:
	docker-compose run --rm composer test

fix:
	docker-compose run --rm composer fix

lint:
	docker-compose run --rm composer lint
