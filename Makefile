ifneq (,$(shell (type docker-compose 2>&1 >/dev/null && echo 1) || true))
	PHP=docker-compose run --rm --no-deps php
else
	PHP=php
endif

PHP_CONSOLE_DEPS=vendor

up: docker-compose.yml
	@docker compose up  -d --build --remove-orphans --quiet-pull

stop:
	@docker compose stop

vendor: composer.json
	@$(PHP) composer install -o -n --no-ansi
	@touch vendor || true

phpcs: $(PHP_CONSOLE_DEPS)
	@$(PHP) vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --using-cache=no -v

phpstan: $(PHP_CONSOLE_DEPS)
	@$(PHP) vendor/bin/phpstan analyse

phpunit: $(PHP_CONSOLE_DEPS)
	@$(PHP) vendor/bin/phpunit --color=always

check: phpcs phpstan phpunit
