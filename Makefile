setup:
	docker compose up -d
	composer install
	npm install
	cp .env.example .env
	php artisan key:generate
	php artisan migrate
	cp .env.backup .env

migrate:
	cp .env .env.backup
	cp .env.example .env
	php artisan migrate
	cp .env.backup .env

migrate-fresh:
	cp .env .env.backup
	cp .env.example .env
	php artisan migrate:fresh
	cp .env.backup .env

up:
	docker compose up -d

down:
	docker compose down

exec: $(cid)
	docker exec -it $(cid) bash

clean:
	php artisan config:clear
	php artisan cache:clear
	composer dump-autoload
