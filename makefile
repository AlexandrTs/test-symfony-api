# make composer COMMAND="install --no-suggest --prefer-dist"
composer:
	docker pull prooph/composer:7.4
	docker run --rm \
	--network host \
	--volume $(CURDIR):${HOME} \
	--volume ${HOME}/.ssh:${HOME}/.ssh:ro \
	--volume /etc/passwd:/etc/passwd:ro \
	--volume /etc/group:/etc/group:ro \
	--user ${shell id -u}:$(shell id -g) \
	--env HOME=${HOME} \
	--env COMPOSER_HOME=${HOME} \
	--workdir ${HOME}  \
	--interactive \
	prooph/composer:7.4 \
	$(COMMAND)

run-server:
	docker pull php:7.4-fpm-alpine3.14
	docker run \
	--rm \
	--volume $(CURDIR):/code \
	--volume /etc/passwd:/etc/passwd:ro \
	--volume /etc/group:/etc/group:ro \
	--user ${shell id -u}:$(shell id -g) \
	--workdir /code \
	--interactive \
	--network host \
	php:7.4-fpm-alpine3.14 \
	php -S localhost:8000 -t public/

run-api-tests:
	docker pull php:7.4-fpm-alpine3.14
	docker run \
	--rm \
	--volume $(CURDIR):/code \
	--volume /etc/passwd:/etc/passwd:ro \
	--volume /etc/group:/etc/group:ro \
	--user ${shell id -u}:$(shell id -g) \
	--workdir /code \
	--interactive \
	--network host \
	--entrypoint vendor/bin/codecept \
	php:7.4-fpm-alpine3.14 \
	run --steps -vvv -d

run-unit-tests:
	docker pull php:7.4-fpm-alpine3.14
	docker run \
	--rm \
	--volume $(CURDIR):/code \
	--volume /etc/passwd:/etc/passwd:ro \
	--volume /etc/group:/etc/group:ro \
	--user ${shell id -u}:$(shell id -g) \
	--workdir /code \
	--interactive \
	--network host \
	--entrypoint vendor/bin/codecept \
	php:7.4-fpm-alpine3.14 \
	run unit -vvv -d

run:
	docker pull php:7.4-fpm-alpine3.14
	docker run \
	--rm \
	--volume $(CURDIR):/code \
	--volume /etc/passwd:/etc/passwd:ro \
	--volume /etc/group:/etc/group:ro \
	--user ${shell id -u}:$(shell id -g) \
	--workdir /code \
	--interactive \
	--network host \
	php:7.4-fpm-alpine3.14 \
	$(COMMAND)