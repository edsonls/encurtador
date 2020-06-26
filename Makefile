deploy_prod:
	docker-compose build --no-cache
	docker-compose up -d -V --remove-orphans

deploy_hml:
	docker-compose build
	docker-compose up -d -V

deploy_dev:
	docker-compose -f docker-compose-dev.yaml build --no-cache
	docker-compose -f docker-compose-dev.yaml up -d -V

down:
	docker-compose down

