all: .build composer-update tests

.PHONY: *

.build:
	sudo docker rm netpromotion/symfony-up || true
	sudo docker build -t netpromotion/symfony-up .

.run:
	sudo docker run -v $$(pwd):/app --rm netpromotion/symfony-up bash -c 'cd /app && ${ARGS}'

composer:
	make .run ARGS="composer ${ARGS}"

composer-update:
	make composer ARGS="update ${ARGS}"

tests:
	make .run ARGS="php vendor/bin/phpunit ${ARGS}"
