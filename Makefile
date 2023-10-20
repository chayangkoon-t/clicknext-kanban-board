setup:
	docker compose up -d
	composer install
	npm install
	cp .env.migrate .env
	php artisan key:generate
	php artisan migrate
	cp .env.docker .env
	php artisan key:generate

migrate:
	cp .env .env.backup
	cp .env.migrate .env
	php artisan migrate
	cp .env.docker .env

migrate-fresh:
	cp .env .env.backup
	cp .env.migrate .env
	php artisan migrate:fresh
	cp .env.docker .env

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
