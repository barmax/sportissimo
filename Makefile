# Containers
APP_CONTAINER_NAME := sportissimo-app
DB_CONTAINER_NAME := sportissimo-db

# Command lines
docker_bin := $(shell command -v docker 2> /dev/null)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)

composer_bin := composer

install: recreate-dev ## Install application dependencies into application container
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(composer_bin) install --no-interaction --ansi --no-suggest

# Dev commands
build-dev: ## Sources - build Docker image locally (dev-local)
	$(docker_compose_bin) rm -f
	$(docker_compose_bin) build

up: ## Start all containers (in background) for development
	$(docker_compose_bin) up --no-recreate -d

recreate-dev: ## Start all containers (in background) with recreate for development
	$(docker_compose_bin) rm -f $(docker ps -f "status=exited" -q) >/dev/null 2>&1
	$(docker_compose_bin) up -d --remove-orphans

down-dev: ## Stop all started for development containers (dev-local)
	$(docker_compose_bin) down

shell: ## Start shell into application container
	$(docker_bin) exec -ti "$(APP_CONTAINER_NAME)" /bin/bash

create-table:
	$(docker_bin) exec -ti "$(DB_CONTAINER_NAME)"  mysql --user="sportissimo" --password="secret" --database="brands" --execute="USE brands; CREATE TABLE IF NOT EXISTS brands ( id int NOT NULL AUTO_INCREMENT, name varchar(255) NOT NULL,created_at datetime NOT NULL, updated_at datetime DEFAULT NULL, PRIMARY KEY (id), UNIQUE KEY i_name (name));"



