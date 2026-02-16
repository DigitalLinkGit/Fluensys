.PHONY: help build up down restart ps logs logs-follow shell db-shell exec clean prune status stop start

# Variables
DOCKER_COMPOSE = docker compose
PROJECT_NAME = fluensys

# Couleurs pour l'affichage
BLUE = \033[0;34m
GREEN = \033[0;32m
YELLOW = \033[0;33m
RED = \033[0;31m
NC = \033[0m # No Color

## —— Makefile pour Fluensys 🚀 ——————————————————————————————————————
help: ## Affiche cette aide
	@echo "$(BLUE)Makefile pour la gestion des conteneurs Docker de Fluensys$(NC)"
	@echo ""
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "$(GREEN)%-20s$(NC) %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker Compose ————————————————————————————————————————————————
build: ## Construit les images Docker
	@echo "$(BLUE)Construction des images Docker...$(NC)"
	$(DOCKER_COMPOSE) build

up: ## Démarre tous les conteneurs en arrière-plan
	@echo "$(BLUE)Démarrage des conteneurs...$(NC)"
	$(DOCKER_COMPOSE) up -d
	@echo "$(GREEN)✓ Conteneurs démarrés avec succès$(NC)"

down: ## Arrête et supprime tous les conteneurs
	@echo "$(BLUE)Arrêt des conteneurs...$(NC)"
	$(DOCKER_COMPOSE) down
	@echo "$(GREEN)✓ Conteneurs arrêtés$(NC)"

restart: down up ## Redémarre tous les conteneurs

stop: ## Arrête les conteneurs sans les supprimer
	@echo "$(BLUE)Arrêt des conteneurs...$(NC)"
	$(DOCKER_COMPOSE) stop

start: ## Démarre les conteneurs existants
	@echo "$(BLUE)Démarrage des conteneurs...$(NC)"
	$(DOCKER_COMPOSE) start

ps: ## Liste les conteneurs en cours d'exécution
	$(DOCKER_COMPOSE) ps

status: ps ## Alias pour 'ps'

## —— Logs ——————————————————————————————————————————————————————————
logs: ## Affiche les logs de tous les conteneurs
	$(DOCKER_COMPOSE) logs

logs-follow: ## Suit les logs en temps réel
	$(DOCKER_COMPOSE) logs -f

logs-app: ## Affiche les logs du conteneur principal
	$(DOCKER_COMPOSE) logs $(PROJECT_NAME)

logs-db: ## Affiche les logs de la base de données
	$(DOCKER_COMPOSE) logs fluensys_database

logs-node: ## Affiche les logs du conteneur Node
	$(DOCKER_COMPOSE) logs node

logs-mailer: ## Affiche les logs du serveur mail
	$(DOCKER_COMPOSE) logs mailer

## —— Shell et Accès ————————————————————————————————————————————————
shell: ## Accède au shell du conteneur principal
	@echo "$(BLUE)Connexion au conteneur $(PROJECT_NAME)...$(NC)"
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) sh

db-shell: ## Accède au shell PostgreSQL
	@echo "$(BLUE)Connexion à PostgreSQL...$(NC)"
	$(DOCKER_COMPOSE) exec fluensys_database psql -U fluensys -d fluensys_db

node-shell: ## Accède au shell du conteneur Node
	@echo "$(BLUE)Connexion au conteneur Node...$(NC)"
	$(DOCKER_COMPOSE) exec node sh

exec: ## Exécute une commande dans le conteneur principal (usage: make exec CMD="ls -la")
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) $(CMD)

## —— Symfony ———————————————————————————————————————————————————————
sf-console: ## Accède à la console Symfony (usage: make sf-console CMD="cache:clear")
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) php bin/console $(CMD)

sf-cache-clear: ## Vide le cache Symfony
	@echo "$(BLUE)Vidage du cache Symfony...$(NC)"
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) php bin/console cache:clear
	@echo "$(GREEN)✓ Cache vidé$(NC)"

sf-migrations: ## Exécute les migrations Doctrine
	@echo "$(BLUE)Exécution des migrations...$(NC)"
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) php bin/console doctrine:migrations:migrate --no-interaction

sf-fixtures: ## Charge les fixtures
	@echo "$(BLUE)Chargement des fixtures...$(NC)"
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) php bin/console doctrine:fixtures:load --no-interaction

## —— Base de données ———————————————————————————————————————————————
db-create: ## Crée la base de données
	@echo "$(BLUE)Création de la base de données...$(NC)"
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) php bin/console doctrine:database:create --if-not-exists
	@echo "$(GREEN)✓ Base de données créée$(NC)"

db-drop: ## Supprime la base de données
	@echo "$(YELLOW)Suppression de la base de données...$(NC)"
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) php bin/console doctrine:database:drop --force --if-exists

db-reset: db-drop db-create sf-migrations ## Réinitialise complètement la base de données

db-backup: ## Sauvegarde la base de données
	@echo "$(BLUE)Sauvegarde de la base de données...$(NC)"
	$(DOCKER_COMPOSE) exec fluensys_database pg_dump -U fluensys fluensys_db > backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "$(GREEN)✓ Sauvegarde créée$(NC)"

## —— Node & Assets ————————————————————————————————————————————————
npm-install: ## Installe les dépendances npm
	@echo "$(BLUE)Installation des dépendances npm...$(NC)"
	$(DOCKER_COMPOSE) exec node npm install

npm-watch: ## Lance le watcher npm
	$(DOCKER_COMPOSE) exec node npm run watch

npm-build: ## Compile les assets en production
	@echo "$(BLUE)Compilation des assets...$(NC)"
	$(DOCKER_COMPOSE) exec node npm run build

## —— Composer ——————————————————————————————————————————————————————
composer-install: ## Installe les dépendances Composer
	@echo "$(BLUE)Installation des dépendances Composer...$(NC)"
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) composer install

composer-update: ## Met à jour les dépendances Composer
	@echo "$(BLUE)Mise à jour des dépendances Composer...$(NC)"
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) composer update

## —— Tests ————————————————————————————————————————————————————————
test: ## Lance les tests
	$(DOCKER_COMPOSE) exec $(PROJECT_NAME) php bin/phpunit

## —— Nettoyage ————————————————————————————————————————————————————
clean: ## Supprime les conteneurs, réseaux et volumes
	@echo "$(RED)Nettoyage complet...$(NC)"
	$(DOCKER_COMPOSE) down -v --remove-orphans
	@echo "$(GREEN)✓ Nettoyage terminé$(NC)"

prune: ## Nettoie les images et volumes Docker inutilisés
	@echo "$(RED)Suppression des ressources Docker inutilisées...$(NC)"
	docker system prune -af --volumes
	@echo "$(GREEN)✓ Nettoyage Docker terminé$(NC)"

## —— Installation complète ————————————————————————————————————————
install: build up composer-install npm-install db-create sf-migrations ## Installation complète du projet
	@echo "$(GREEN)✓ Installation terminée !$(NC)"
	@echo "$(BLUE)Accédez à Mailpit : http://localhost:8025$(NC)"

## —— URLs utiles ——————————————————————————————————————————————————
urls: ## Affiche les URLs utiles
	@echo "$(BLUE)URLs du projet :$(NC)"
	@echo "  - Application : http://localhost"
	@echo "  - Mailpit : http://localhost:8025"
	@echo "  - PostgreSQL : localhost:5432"
