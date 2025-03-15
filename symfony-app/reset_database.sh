#!/bin/bash

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Script de réinitialisation de la base de données 'voitures'${NC}"

# Définition directe des informations de connexion
DB_USER="tianh"
DB_PASSWORD="tianh"
DB_HOST="localhost"
DB_PORT="3306"
DB_NAME="voitures"

echo -e "${YELLOW}Connexion à MySQL avec l'utilisateur ${DB_USER}${NC}"

# Affichage des informations de connexion pour débogage
echo -e "${YELLOW}Informations de connexion:${NC}"
echo -e "Utilisateur: ${DB_USER}"
echo -e "Hôte: ${DB_HOST}"
echo -e "Port: ${DB_PORT}"
echo -e "Base de données: ${DB_NAME}"

# 1. Suppression de la base de données existante
echo -e "${YELLOW}Suppression de la base de données ${DB_NAME} si elle existe...${NC}"

# Utilisation de l'option -p avec le mot de passe
mysql -u"$DB_USER" -p"$DB_PASSWORD" -h"$DB_HOST" -P"$DB_PORT" -e "DROP DATABASE IF EXISTS ${DB_NAME};"

if [ $? -eq 0 ]; then
    echo -e "${GREEN}Base de données supprimée avec succès.${NC}"
else
    echo -e "${RED}Erreur lors de la suppression de la base de données.${NC}"
    echo -e "${YELLOW}Tentative alternative avec demande de mot de passe...${NC}"
    # Tentative alternative avec demande interactive du mot de passe
    mysql -u"$DB_USER" -p -h"$DB_HOST" -P"$DB_PORT" -e "DROP DATABASE IF EXISTS ${DB_NAME};"
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Base de données supprimée avec succès.${NC}"
    else
        echo -e "${RED}Échec de la suppression de la base de données.${NC}"
        exit 1
    fi
fi

# 2. Création d'une nouvelle base de données
echo -e "${YELLOW}Création d'une nouvelle base de données ${DB_NAME}...${NC}"

# Utilisation de l'option -p avec le mot de passe
mysql -u"$DB_USER" -p"$DB_PASSWORD" -h"$DB_HOST" -P"$DB_PORT" -e "CREATE DATABASE ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if [ $? -eq 0 ]; then
    echo -e "${GREEN}Base de données créée avec succès.${NC}"
else
    echo -e "${RED}Erreur lors de la création de la base de données.${NC}"
    echo -e "${YELLOW}Tentative alternative avec demande de mot de passe...${NC}"
    # Tentative alternative avec demande interactive du mot de passe
    mysql -u"$DB_USER" -p -h"$DB_HOST" -P"$DB_PORT" -e "CREATE DATABASE ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Base de données créée avec succès.${NC}"
    else
        echo -e "${RED}Échec de la création de la base de données.${NC}"
        exit 1
    fi
fi

# 3. Nettoyer le dossier des migrations existantes
echo -e "${YELLOW}Nettoyage des migrations existantes...${NC}"
MIGRATIONS_DIR="migrations"

if [ -d "$MIGRATIONS_DIR" ]; then
    # Conserver uniquement le fichier Version.php et supprimer les autres migrations
    find "$MIGRATIONS_DIR" -type f -name "Version*.php" -delete
    echo -e "${GREEN}Migrations existantes supprimées.${NC}"
else
    echo -e "${YELLOW}Aucun dossier de migrations trouvé. Création du dossier...${NC}"
    mkdir -p "$MIGRATIONS_DIR"
fi

# 4. Utiliser doctrine:schema:update au lieu des migrations
echo -e "${YELLOW}Création du schéma de base de données...${NC}"
php bin/console doctrine:schema:update --force --complete

if [ $? -eq 0 ]; then
    echo -e "${GREEN}Schéma de base de données créé avec succès.${NC}"
else
    echo -e "${RED}Erreur lors de la création du schéma de base de données.${NC}"
    exit 1
fi

echo -e "${GREEN}La base de données ${DB_NAME} a été réinitialisée avec succès !${NC}" 