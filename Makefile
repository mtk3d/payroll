lint: ## Execute all available linters
	$(SYMFONY_CONSOLE) lint:container
	$(SYMFONY_CONSOLE) lint:twig
	$(PSALM)

fix: ## Fix all code formatting problems
	$(PHP_CS_FIXER) fix

test: ## Run tests
	$(PHPUNIT)

.PHONY: lint test fix
.DEFAULT_GOAL=help

help:
	@printf "\033[33mUsage:\033[0m\n  make TARGET\n\033[33m\nTargets:\n"
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//' | \
	awk 'BEGIN {FS = ":"}; {printf "  \033[33m%-10s\033[0m%s\n", $$1, $$2}'

SYMFONY_CONSOLE=./bin/console
PSALM=./bin/psalm
PHP_CS_FIXER=./bin/php-cs-fixer
PHPUNIT=./bin/phpunit
