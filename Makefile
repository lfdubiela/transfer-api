PWD:= $(shell pwd -L)

DOCKER_FLYWAY_MIGRATIONS_VOLUME=-v ${PWD}/resources/migrations/sql:/flyway/sql
DOCKER_FLYWAY_CONF_VOLUME=-v ${PWD}/resources/migrations/conf:/flyway/conf
DOCKER_FLYWAY=flyway/flyway:6.5.5
DOCKER_FLYWAY_RUN=docker run --rm ${DOCKER_FLYWAY_MIGRATIONS_VOLUME} ${DOCKER_FLYWAY_CONF_VOLUME} --network=user_my-network ${DOCKER_FLYWAY}
DOCKER_PHP=docker exec -it wallet-php-fpm

COMPOSER_PATH = vendor

# Run application
run:
	- docker-compose up -d

# Update (composer)
update:
	- @if [ ! -d $(COMPOSER_PATH) ]; then ${DOCKER_PHP} composer install; else ${DOCKER_PHP} composer update; fi

# Remove containers
remove:
	- docker rm wallet-mysql wallet-php-fpm wallet-webserver

# Stop Containers
stop:
	- docker stop wallet-mysql wallet-php-fpm wallet-webserver

# Flyway migrate
migrate:
	- ${DOCKER_FLYWAY_RUN} 'migrate'

# Flyway dry run - preview changes
dry-run:
	- ${DOCKER_FLYWAY_RUN} 'dry run'

# Build review -- not working yet, tem que configurar phpcs no container
build-review:
	- ${DOCKER_PHP} composer code-review

# Update NoDev (Composer)
update-nodev:
	- ${DOCKER_PHP} composer update --ignore-platform-reqs --no-dev --no-progress --optimize-autoloader

# Run unit tests (PhpUnit)
test:
	- ${DOCKER_PHP} composer test

open-report:
	- sensible-browser ./report/html/index.html

# Run unit tests (PhpUnit) + coverage report
test-report:
	- ${DOCKER_PHP} composer test
	- sensible-browser ./report/html/index.html

# Code Sniffer (PHPCS)
sniffer:
	- composer sniffer

# Code Sniffer (PHPCS) + report File
sniffer-report:
	- composer sniffer-report
	- sensible-browser ./report/sniffer.xml

# Show Mess (PHPMD)
mess:
	- composer mess --dev

# Show Mess (PHPMD) + report File
mess-report:
	- composer mess-report
	- sensible-browser ./report/mess.html

# Code review (sniffer + mess)
code-review:
	- composer code-review'
	- composer test'

# Code review report (sniffer + mess) + report Files
code-review-report:
	- mkdir -p ./report
	- composer code-review-report
	- composer test
	- sensible-browser ./report/

# Coding Standards Fixer (php-cs-fixer)
fix-code:
	- php-cs-fixer fix src/ --rules=@PSR2
	- git status