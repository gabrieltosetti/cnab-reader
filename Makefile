up:
	docker compose -f docker/docker-compose.yml up -d --force-recreate

down:
	docker compose -f docker/docker-compose.yml down

restart:
	make down
	make up

php-up:
	docker compose -f docker/docker-compose.yml up php -d --force-recreate

php-down:
	docker compose -f docker/docker-compose.yml stop php

php-restart:
	make php-down
	make php-up

php-logs:
	docker compose -f docker/docker-compose.yml logs -f php

php-bash:
	docker compose -f docker/docker-compose.yml exec php bash

php-composer-install:
	docker compose -f docker/docker-compose.yml exec php composer install

webgrind-up:
	docker compose -f docker/docker-compose.yml up webgrind -d --force-recreate

webgrind-down:
	docker compose -f docker/docker-compose.yml stop webgrind

webgrind-restart:
	make webgrind-down
	make webgrind-up