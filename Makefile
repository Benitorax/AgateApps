DOCKER_COMPOSE  = docker-compose

EXEC_PHP        = $(DOCKER_COMPOSE) exec php
EXEC_JS         = $(DOCKER_COMPOSE) exec node
EXEC_DB         = $(DOCKER_COMPOSE) exec db
EXEC_QA         = $(DOCKER_COMPOSE) exec qa

SYMFONY         = $(EXEC_PHP) bin/console
COMPOSER        = $(EXEC_PHP) composer
NPM             = $(EXEC_JS) npm

TEST_DBNAME = test_agate_portal
PORTAL_DBNAME = agate_portal
PORTAL_DBPWD = db

CURRENT_DATE = `date "+%Y-%m-%d_%H-%M-%S"`

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
install: build node_modules start vendor db test-db fixtures assets map-tiles
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
	rm -rf vendor node_modules build var/cache/* var/log/* var/sessions/*
.PHONY: clean

##
## Tools
## -----
##

cc: ## Clear and warmup PHP cache
	$(SYMFONY) cache:clear --no-warmup
	$(SYMFONY) cache:warmup
.PHONY: cc

db: ## Reset the database
	@echo "Waiting for database..."
	@while ! $(EXEC_DB) mysql -uroot -p$(PORTAL_DBPWD) -e "SELECT 1;" > /dev/null 2>&1; do sleep 0.5 ; done
	-$(SYMFONY) doctrine:database:drop --if-exists --force
	-$(SYMFONY) doctrine:database:create --if-not-exists
	-$(SYMFONY) doctrine:migrations:migrate --no-interaction
.PHONY: db

test-db: ## Create a proper database for testing
	@echo "Waiting for database..."
	@while ! $(EXEC_DB) mysql -uroot -p$(PORTAL_DBPWD) -e "SELECT 1;" > /dev/null 2>&1; do sleep 0.5 ; done
	-$(SYMFONY) --env=test doctrine:database:drop --if-exists --force
	-$(SYMFONY) --env=test doctrine:database:create
	-$(SYMFONY) --env=test doctrine:schema:create
	-$(SYMFONY) --env=test doctrine:fixtures:load --append --no-interaction
.PHONY: tests-db

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
	@if [ "${AGATE_DEPLOY_REMOTE}" = "" ]; then \
		echo "[ERROR] Please specify the AGATE_DEPLOY_REMOTE env var to connect to a remote" ;\
		exit 1 ;\
	fi; \
	if [ "${AGATE_DEPLOY_DIR}" = "" ]; then \
		echo "[ERROR] Please specify the AGATE_DEPLOY_DIR env var to determine which directory to use in prod" ;\
		exit 1 ;\
	fi; \
	ssh ${AGATE_DEPLOY_REMOTE} ${AGATE_DEPLOY_DIR}/../dump_db.bash > var/dump.sql

fixtures: ## Install all dev fixtures in the database
	-$(SYMFONY) --env=dev doctrine:fixtures:load --append --no-interaction
.PHONY: fixtures

watch: ## Run Gulp to compile assets on change
	$(NPM) run watch
.PHONY: watch

assets: ## Run Gulp to compile assets
assets: node_modules
	$(NPM) run dump
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

##
## Tests
## -----
##

start-php:
	$(DOCKER_COMPOSE) up -d --remove-orphans php
.PHONY: start-php

start-qa:
	$(DOCKER_COMPOSE) up -d --remove-orphans qa
.PHONY: start-qa

install-php: ## Prepare environment to execute PHP tests
install-php: build start vendor db test-db fixtures
.PHONY: install-php

install-node: ## Prepare environment to execute NodeJS tests
install-node: build node_modules start
.PHONY: install-node

php-tests: ## Execute checks & tests
php-tests: start-qa checks phpunit phpstan cs-dry-run
.PHONY: php-tests

phpstan: ## Execute phpstan
phpstan: start-qa
	$(EXEC_QA) phpstan analyse -c phpstan.neon
.PHONY: phpstan

cs: ## Execute php-cs-fixer
cs: start-qa
	$(EXEC_QA) php-cs-fixer fix
.PHONY: cs

cs-dry-run: ## Execute php-cs-fixer with a simple dry run
cs-dry-run: start-qa
	$(EXEC_QA) php-cs-fixer fix --dry-run
.PHONY: cs-dry-run

node-tests: ## Execute checks & tests
node-tests: start
	$(EXEC_JS) npm run-script test --verbose -LLLL
.PHONY: node-tests

checks: ## Execute CS, linting and security checks
checks:
	$(EXEC_QA) bin/console lint:twig templates src
	$(EXEC_QA) bin/console lint:yaml --parse-tags config
	$(EXEC_QA) bin/console lint:yaml --parse-tags src
	$(EXEC_QA) security-checker security:check
.PHONY: checks

phpunit: ## Execute all PHPUnit tests
phpunit: start-php
	$(EXEC_PHP) bin/phpunit --log-junit=build/log/logfile.xml
.PHONY: phpunit

coverage: ## Retrieves the code coverage of the phpunit suite
coverage: start-qa
	$(EXEC_QA) phpdbg -qrr bin/phpunit --coverage-html=build/coverage/$(CURRENT_DATE) --coverage-clover=build/coverage.xml
.PHONY: coverage

##
## Agate
## -----
##

map-tiles: ## Dump built-in EsterenMap maps tiles to the public directory
	-$(SYMFONY) esterenmaps:map:generate-tiles 1 --no-interaction
.PHONY: map-tiles
