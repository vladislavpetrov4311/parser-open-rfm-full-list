-include .env

# Имя проекта
PROJECT_NAME = lib-parser-open-rfm-full-list
NETWORK_NAME ?= production_default

# Путь к Docker-образу в container registry. ПОКА НА ОСНОВЕ УЗЕБКОВ, НУЖНО БУДЕТ СОЗДАТЬ НА ОСВНОВЕ НЕГО НОВЫЙ ОБРАЗ ДЛЯ ЛИБЫ ОТКРЫТОГО РФМ
REGISTRY_IMAGE = cr.yandex/crpfo3k0vaqi88j7921p/cloud-functions/uzbekistan-terrorists-parsing

# Версия составляется по приоритету git tag > branch > commit
VERSION = $(shell git describe --tags --exact-match 2> /dev/null || git symbolic-ref -q --short HEAD || git rev-parse --short HEAD || echo "noversion")

OUTPUT_FILE_PATH ?=
INPUT_FILE_PATH ?=
OUTPUT_FILE_NAME ?=
INPUT_FILE_NAME ?=

COMPOSER_GITLAB_TOKEN ?=

DOCKER_SETTINGS = --env NAMESPACE=$(NAMESPACE) \
	--env OUTPUT_FILE_PATH=$(OUTPUT_FILE_PATH) \
	--env INPUT_FILE_PATH=$(INPUT_FILE_PATH) \
	--env OUTPUT_FILE_NAME=$(OUTPUT_FILE_NAME) \
	--env INPUT_FILE_NAME=$(INPUT_FILE_NAME) \
	--network=$(NETWORK_NAME)

build:
	docker build --pull -t $(REGISTRY_IMAGE):$(VERSION) --build-arg COMPOSER_GITLAB_TOKEN=$(COMPOSER_GITLAB_TOKEN) .

pull:
	docker pull $(REGISTRY_IMAGE):$(VERSION)

push:
	docker push $(REGISTRY_IMAGE):$(VERSION)

up: down
	@docker network create $(NETWORK_NAME) || true
	@docker run --rm --name $(PROJECT_NAME) \
		$(DOCKER_SETTINGS) \
		$(REGISTRY_IMAGE):$(VERSION)

dev: down
	@docker build -t $(PROJECT_NAME)-dev --build-arg COMPOSER_GITLAB_TOKEN=$(COMPOSER_GITLAB_TOKEN) .
	@docker network create $(NETWORK_NAME) || true
	@docker run  --rm --name $(PROJECT_NAME)-dev \
		$(DOCKER_SETTINGS) \
		$(PROJECT_NAME)-dev

console: down
	@docker build -t $(PROJECT_NAME)-dev --build-arg COMPOSER_GITLAB_TOKEN=$(COMPOSER_GITLAB_TOKEN) .
	@docker network create $(NETWORK_NAME) || true
	@docker run  --rm --name $(PROJECT_NAME)-dev \
		$(DOCKER_SETTINGS) \
		-v "$(PWD):/app"  \
		-it \
		$(PROJECT_NAME)-dev sh

down:
	docker stop $(PROJECT_NAME) && docker container rm $(PROJECT_NAME) || true

test:
	@docker build -t $(PROJECT_NAME)-test --build-arg COMPOSER_GITLAB_TOKEN=$(COMPOSER_GITLAB_TOKEN) .
	@docker run --rm --name $(PROJECT_NAME)_testing \
		$(PROJECT_NAME)-test vendor/bin/codecept run

env:
	@echo "# Путь к директории для сохранения файла с чистыми данными \n\
	OUTPUT_FILE_PATH=$(OUTPUT_FILE_PATH)\n\
	\n\
	# Путь к директории в которой храниться файл последней версии \n\
	INPUT_FILE_PATH=$(INPUT_FILE_PATH)\n\
	\n\
	# Имя файла с чистыми данными\n\
	OUTPUT_FILE_NAME=$(OUTPUT_FILE_NAME)\n\
	\n\
	# Имя файла последней версии\n\
	INPUT_FILE_NAME=$(INPUT_FILE_NAME)\n\
	\n\
	# Токен авторизации в GitLab\n\
	# Требуется для билда\n\
	# Получить токен: https://incident-center.gitlab.yandexcloud.net/-/profile/personal_access_tokens\n\
	# Поставить галочку read_api\n\
	COMPOSER_GITLAB_TOKEN=$(COMPOSER_GITLAB_TOKEN)\n\
	" > .env