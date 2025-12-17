# Use bash for command execution for its advanced features like arrays and functions.
SHELL := /bin/bash

# параметры базы данных
DB_HOST = 127.0.0.1
DB_NAME = mobicms
DB_USER = root
DB_PASS = root
TABLES_TO_TRUNCATE = system__session
DUMP_FILE = .docker/db-import/dump.sql

# --- Color Codes ---
# Using the 8-bit (256-color) palette for maximum compatibility.
COLOR_GREEN := \e[1;32m
COLOR_PURPLE := \e[0;95m
COLOR_RESET := \e[0m

# Default target to run when 'make' is called without arguments.
.DEFAULT_GOAL := help

help: ## Show this help message
	@echo "============================================================"
	@echo "Docker Environment"
	@echo "------------------------------------------------------------"
#	@echo ""
	@echo "Core Commands:"
	@echo -e "        $(COLOR_GREEN)make up$(COLOR_RESET) - Start all services"
	@echo -e "      $(COLOR_GREEN)make stop$(COLOR_RESET) - Stop all containers"
	@echo -e "      $(COLOR_GREEN)make down$(COLOR_RESET) - Stop and remove all containers"
	@echo -e "     $(COLOR_GREEN)make build$(COLOR_RESET) - Build or rebuild Docker images"
	@echo -e "   $(COLOR_GREEN)make db-dump$(COLOR_RESET) - Dump database"
	@echo -e "$(COLOR_GREEN)make db-restore$(COLOR_RESET) - Restore database"
	@echo ""
	@echo "Links:"
	@echo -e "      $(COLOR_PURPLE)Frontend$(COLOR_RESET) - https://localhost"
	@echo -e "       $(COLOR_PURPLE)Backend$(COLOR_RESET) - https://localhost/admin-127486"
	@echo -e "       $(COLOR_PURPLE)Mailhog$(COLOR_RESET) - http://localhost:8025"
	@echo ""

build:
	docker compose build

up:
	@echo "Starting services..."
	docker compose up -d

stop:
	@echo "Stopping services..."
	docker compose stop

down:
	@echo "Stopping services..."
	docker compose down

db-dump:
	@echo "Truncating tables in $(DB_NAME)..."
	@for t in $(TABLES_TO_TRUNCATE); do \
    	echo "TRUNCATE TABLE $$t;" | mariadb -h $(DB_HOST) -u $(DB_USER) -p$(DB_PASS) $(DB_NAME); \
    done
	@echo "Dumping database $(DB_NAME) from $(DB_HOST)..."
	@mariadb-dump --add-drop-table -h $(DB_HOST) -u $(DB_USER) -p$(DB_PASS) $(DB_NAME) > $(DUMP_FILE)
	@echo "Dump saved to $(DUMP_FILE)"

db-restore:
	@echo "Restoring database $(DB_NAME) from $(DB_HOST)..."
	@mariadb -h $(DB_HOST) -u $(DB_USER) -p$(DB_PASS) $(DB_NAME) < $(DUMP_FILE)
	@echo "Restore complete"
