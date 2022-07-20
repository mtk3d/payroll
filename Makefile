up: ## Up and run docker environment
up: .env docker-compose-up composer-install migrate load-fixtures
	@echo ""
	@echo "Application is now available on: http://127.0.0.1:9090"

down: ## Stop environment
	$(DOCKER_COMPOSE) down

shell: ## Go inside docker container
	$(DOCKER_COMPOSE) exec -it -- app sh

lint: ## Execute all available linters
	$(SYMFONY_CONSOLE) lint:container
	$(SYMFONY_CONSOLE) lint:twig
	$(PSALM)

fix: ## Fix all code formatting problems
	$(PHP_CS_FIXER) fix

test: ## Run tests
	$(PHPUNIT)

test-%: ## Run specific tests `test-[all|unit|integration|functional]`
	$(PHPUNIT) --testsuite=$*

.PHONY: up shell lint test test-% fix docker-compose-up composer-install migrate load-fixtures
.DEFAULT_GOAL=help

help:
	@printf "\033[33mUsage:\033[0m\n  make TARGET\n\033[33m\nTargets:\n"
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//' | \
	awk 'BEGIN {FS = ":"}; {printf "  \033[33m%-10s\033[0m%s\n", $$1, $$2}'

.env:
	@cp .env.dist .env

docker-compose-up:
	@$(DOCKER_COMPOSE) up -d

composer-install:
	@$(DOCKER_COMPOSE) exec -it -- app composer install

migrate:
	@$(DOCKER_COMPOSE) exec -it -- app $(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction

load-fixtures:
	@$(DOCKER_COMPOSE) exec -it -- app $(SYMFONY_CONSOLE) doctrine:fixtures:load --no-interaction

SYMFONY_CONSOLE=./bin/console
PSALM=./bin/psalm
PHP_CS_FIXER=./bin/php-cs-fixer
PHPUNIT=./bin/phpunit
DOCKER_COMPOSE=docker-compose -f deployments/docker-compose.yml
