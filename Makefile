test:
	docker exec -it local-brand-x-challenge php artisan test
migrate:
	docker exec -it local-brand-x-challenge php artisan migrate
migrate-fresh:
	docker exec -it local-brand-x-challenge php artisan migrate:fresh
