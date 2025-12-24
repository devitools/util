.PHONY: up down logs test shell network setup

CONTAINER := app
NETWORK := reverse-proxy

network:
	docker network inspect $(NETWORK) >/dev/null 2>&1 || docker network create $(NETWORK)

setup: network up

up:
	docker-compose up -d

down:
	docker-compose down

logs:
	docker-compose logs -f $(CONTAINER)

test:
	docker-compose exec $(CONTAINER) php tests/run.php

shell:
	docker-compose exec -it $(CONTAINER) bash
