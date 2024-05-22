.PHONY: start init load-fixtures auth-token

start:
	docker-compose up -d
	docker-compose exec -T php bash -c "composer install"
	docker-compose exec -T php bash -c "php bin/console cache:clear"
	docker-compose exec -T php bash -c "php bin/console doctrine:cache:clear-meta"
	docker-compose exec -T php bash -c "php bin/console doctrine:migrations:migrate --allow-no-migration --no-interaction --no-debug"
	docker-compose exec -T php bash -c "php bin/console assets:install"

init:
	cp .env.example .env
	cp /app/.env /app/.env.local
	$(MAKE) start
	$(MAKE) load-fixtures
	$(MAKE) auth-token

load-fixtures:
	docker-compose exec -T php bash -c "php bin/console d:f:l --no-interaction"

auth-token:
	docker-compose exec -T php bash -c "php bin/console app:user:get-auth-token"
