# Containers
APP_CONTAINER_NAME := sportissimo-app

# Command lines
docker_bin := $(shell command -v docker 2> /dev/null)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)
docker_compose_production := $(shell command -v docker-compose 2> /dev/null) -f docker-compose.prod.yml

artisan_bin := php artisan --no-interaction -vvv --no-ansi
composer_bin := composer

# Production commands
build: ## Build Docker image locally (production)
	$(docker_compose_production) rm -f
	$(docker_compose_production) build

recreate: ## Start all containers (in background) with recreate for production
	$(docker_compose_production) rm -f $(docker ps -f "status=exited" -q) >/dev/null 2>&1
	$(docker_compose_production) up -d $(APP_SERVICE_NAME)

down: ## Stop all started for development containers (production)
	$(docker_compose_production) stop $(APP_SERVICE_NAME)

install: recreate ## Install application dependencies into application container
	$(docker_compose_production) exec -t "$(APP_CONTAINER_NAME)" $(composer_bin) install --ansi --no-dev

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

install-dev: recreate-dev ## Install application dependencies with dev deps into application container
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(composer_bin) install --no-interaction --ansi

migrate: ## Migrate
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(artisan_bin) migrate

migrate-force: ## Migrate with force
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(artisan_bin) migrate --force

refresh: ## Refresh migration & seed database
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(artisan_bin) migrate:refresh

migrate-seed: migrate seed ## Run migration with seeds

refresh-seed: refresh seed ## Run migration with seeds

seed: ## Seed data to db
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(artisan_bin) db:seed

fixer: ## Run fixes for code style
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" vendor/bin/php-cs-fixer fix -v

fixer-strict: fixer ## Run fixer with psalter
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" vendor/bin/psalter --php-version=8.1 --issues=MissingReturnType,InvalidReturnType,InvalidNullableReturnType,InvalidFalsableReturnType,MissingParamType,MissingPropertyType,MismatchingDocblockParamType,MismatchingDocblockReturnType,LessSpecificReturnType,PossiblyUndefinedVariable,UnusedProperty

clear-cache: ## Clear cache of application
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(artisan_bin) optimize:clear
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(artisan_bin) event:clear

linter: ## Run code checks
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" vendor/bin/php-cs-fixer fix -v --dry-run --stop-on-violation
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" vendor/bin/psalm --show-info=false

create-default-admin: ## Create an admin account
	$(docker_bin) exec "$(APP_CONTAINER_NAME)" $(artisan_bin) admin:create:default

test: up ## Execute application tests
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(artisan_bin) optimize:clear
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(artisan_bin) cache:clear
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" vendor/bin/codecept build
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" vendor/bin/codecept clean
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" vendor/bin/codecept run -v --fail-fast

generate-docs: ## Generates swagger-docs. You can watch the docs locally on http://localhost:80/api/documentation
	$(docker_bin) exec -t "$(APP_CONTAINER_NAME)" $(artisan_bin) l5-swagger:generate
