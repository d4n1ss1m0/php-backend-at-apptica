DOCKER_COMPOSE = docker compose
PHP = $(DOCKER_COMPOSE) exec app php
COMPOSER = $(DOCKER_COMPOSE) exec app composer
ARTISAN = $(PHP) artisan
NPM = $(DOCKER_COMPOSE) exec app npm

install:
	@test -f ./laravel/.env || cp ./laravel/.env.example ./laravel/.env
	make build
	$(DOCKER_COMPOSE) exec app composer install
	$(ARTISAN) migrate --seed

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

build:
	$(DOCKER_COMPOSE) up -d --build
