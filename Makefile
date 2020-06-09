install:
	composer install

lint:
	composer run-script phpcs -- --standard=PSR12 src

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 src

test:
	composer phpunit tests