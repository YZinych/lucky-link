include .env

APP=lucky_app
QUEUE_CONTAINER=queue
SCHEDULER_CONTAINER=scheduler

dev:
	docker compose up -d
	#open http://localhost:${FORWARD_APP_PORT}

dev-b:
	docker compose up -d --build
	#open http://localhost:${FORWARD_APP_PORT}

down:
	docker compose down

bash:
	docker exec -it $(APP) bash

bash-root:
	docker exec -it -u root $(APP) bash

cache-clear:
	docker exec -it $(APP) php artisan config:clear && \
	docker exec -it $(APP) php artisan cache:clear && \
	docker exec -it $(APP) php artisan view:clear

queue-log:
	docker compose logs -f $(QUEUE_CONTAINER)

schedule-log:
	docker compose logs -f $(SCHEDULER_CONTAINER)
