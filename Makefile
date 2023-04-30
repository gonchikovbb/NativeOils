.PHONY: dev-up
dev-up:
	docker-compose build && docker-compose up -d
