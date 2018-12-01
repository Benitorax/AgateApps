DOCKER_COMPOSE  = docker-compose

EXEC_PHP        = $(DOCKER_COMPOSE) exec php
EXEC_JS         = $(DOCKER_COMPOSE) exec node
EXEC_DB         = $(DOCKER_COMPOSE) exec db

SYMFONY         = $(EXEC_PHP) bin/console
COMPOSER        = $(EXEC_PHP) composer
NPM             = $(EXEC_JS) npm

PORTAL_DBNAME = agate_portal
PORTAL_DBPWD = agate_portal

##
## Project
## -------
##

.DEFAULT_GOAL := help
help: ## Show this help message
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help

install: ## Install and start the project
install: .env build node_modules start vendor db fixtures assets map-tiles
.PHONY: install

build: ## Build the Docker images
	$(DOCKER_COMPOSE) build --force-rm --compress
.PHONY: build

start: ## Start the project
	$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate
.PHONY: start

stop: ## Stop the project
	$(DOCKER_COMPOSE) stop
.PHONY: stop

kill: ## Stop all containers
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --volumes --remove-orphans
.PHONY: kill

reset: ## Stop and start a fresh install of the project
reset: kill install
.PHONY: reset

clean: ## Stop the project and remove generated files and configuration
clean: kill
	rm -rf .env vendor node_modules build var/cache/* var/log/* var/sessions/*
.PHONY: clean

##
## Tools
## -----
##

cc: ## Clear and warmup PHP cache
	$(SYMFONY) cache:clear --no-warmup
	$(SYMFONY) cache:warmup

db: ## Reset the database
db:
	-$(SYMFONY) doctrine:database:drop --if-exists --force
	-$(SYMFONY) doctrine:database:create --if-not-exists
	-$(SYMFONY) doctrine:migrations:migrate --no-interaction
.PHONY: db

prod-db: ## Installs production database if it has been saved in "var/dump.sql". You have to download it manually.
prod-db: var/dump.sql
	@if [ -f var/dump.sql ]; then \
        $(SYMFONY) doctrine:database:drop --if-exists --force ;\
        $(SYMFONY) doctrine:database:create --if-not-exists ;\
		$(EXEC_DB) mysql -uroot -p$(PORTAL_DBPWD) $(PORTAL_DBNAME) -e "source /srv/dump.sql" ;\
		$(SYMFONY) doctrine:migrations:migrate -n ;\
	else \
		echo "No prod database to process. Download it and save it to var/dump.sql." ;\
	fi;

var/dump.sql: ## Tries to download a database from production environment
var/dump.sql:
	@if [ "${AGATE_DEPLOY_REMOTE}" = "" ]; then \
		echo "[ERROR] Please specify the AGATE_DEPLOY_REMOTE env var to connect to a remote" ;\
		exit 1 ;\
	fi; \
	if [ "${AGATE_DEPLOY_DIR}" = "" ]; then \
		echo "[ERROR] Please specify the AGATE_DEPLOY_DIR env var to determine which directory to use in prod" ;\
		exit 1 ;\
	fi; \
	ssh ${AGATE_DEPLOY_REMOTE} ${AGATE_DEPLOY_DIR}/../dump_db.bash > var/dump.sql

fixtures: ## Install all fixtures in the database
fixtures:
	-$(SYMFONY) doctrine:fixtures:load --append --no-interaction
.PHONY: fixtures

assets: ## Run Gulp to compile assets
assets: node_modules
	$(NPM) run gulp dump
.PHONY: assets

composer.lock: ## Update lockfile
composer.lock: composer.json
	$(COMPOSER) update --lock --no-scripts --no-interaction

vendor: ## Install PHP vendors
vendor: composer.lock
	$(COMPOSER) install

node_modules: ## Install JS vendors
node_modules: package-lock.json
	$(DOCKER_COMPOSE) run node npm install
	$(DOCKER_COMPOSE) up -d node

.env: ## Create an `.env` file if it does not exist
.env: .env.dist
	@if [ -f .env ]; \
	then\
		echo '\033[1;41m/!\ The .env.dist file has changed. Please check your .env file (this message will not be displayed again).\033[0m';\
		touch .env;\
		exit 1;\
	else\
		echo cp .env.dist .env;\
		cp .env.dist .env;\
	fi

##
## Tests
## -----
##

install-php: ## Prepare environment to execute PHP tests
install-php: build start vendor db fixtures
.PHONY: install-php

install-node: ## Prepare environment to execute NodeJS tests
install-node: build node_modules start
.PHONY: install-node

php-tests: ## Execute checks & tests
php-tests: start checks phpunit
.PHONY: php-tests

node-tests: ## Execute checks & tests
node-tests: start
	$(EXEC_JS) npm run-script test --verbose -LLLL
.PHONY: node-tests

checks: ## Execute linting and security checks
checks: composer.lock
	$(EXEC_PHP) vendor/bin/security-checker security:check
	$(SYMFONY) lint:twig templates src
	$(SYMFONY) lint:yaml --parse-tags config
	$(SYMFONY) lint:yaml --parse-tags src
.PHONY: checks

phpunit: ## Execute all PHPUnit tests
phpunit: composer.lock
	$(EXEC_PHP) bin/phpunit --log-junit=build/log/logfile.xml
.PHONY: phpunit

phpunit-coverage: ## Execute all PHPUnit tests with code coverage support
phpunit-coverage: composer.lock
	$(EXEC_PHP) docker-php-ext-enable xdebug
	$(EXEC_PHP) bin/phpunit --log-junit=build/log/logfile_coverage.xml --coverage-text --coverage-clover=build/log/coverage.xml
	$(EXEC_PHP) sh -c "php --ini | grep xdebug | sed 's/,\$//' | xargs rm -f"
.PHONY: phpunit

##
## Agate
## -----
##

map-tiles: ## Dump built-in EsterenMap maps tiles to the public directory
	-$(SYMFONY) esterenmaps:map:generate-tiles 1 --no-interaction
.PHONY: map-tiles
