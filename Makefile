PHPUNIT = ./vendor/bin/phpunit

SRC_DIR = src
TEST_DIR = tests

all: test

install:
	composer install

test:
	$(PHPUNIT) $(TEST_DIR)

ecs:
	./vendor/bin/ecs

test-file:
	$(PHPUNIT) $(TEST_DIR)/$(file)

clean:
	rm -rf tests/*.xml tests/logs