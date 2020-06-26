deploy_prod:
	docker-compose build
	docker-compose up --no-deps -d -V

deploy_hml:
	docker-compose build portal-cbm_homologa
	docker-compose up --no-deps -d -V portal-cbm_homologa

deploy_dev:
	docker-compose build portal-cbm_homologa
	docker-compose up --no-deps -d portal-cbm_homologa
	docker exec portal-cbm chmod +x /app/install.sh
	docker exec portal-cbm bash -x /app/install.sh
	docker exec portal-cbm chmod 777 /app/.env
	docker exec portal-cbm chmod 777 -R /app/storage
	docker exec portal-cbm chmod 777 -R /app/public
	docker exec portal-cbm chmod 777 -R /app/storage/logs
