#!/bin/bash

set -e

# bin/ directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Project directory
cd ${DIR}/../

php bin/console cache:clear --no-warmup
php bin/console cache:warmup

php bin/console doctrine:migrations:migrate --no-interaction

php bin/console doctrine:schema:validate || echo 'Doctrine schema not valid, please make sure it is correct.'

npm install

npm run-script deploy

composer install --no-dev --classmap-authoritative --prefer-dist --no-interaction
