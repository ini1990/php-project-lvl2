install:
	composer install
lint:
	composer phpcs -- --standard=PSR12 src bin
test:
	composer test
test-coverage:
	composer test -- --coverage-clover build/logs/clover.xml