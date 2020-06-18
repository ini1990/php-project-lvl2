install:
	composer install
lint:
	composer phpcs -- --standard=PSR12 src bin
test:
	composer test
