.PHONY: up down logs test shell

CONTAINER := app

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
