#!/bin/bash

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Script de nettoyage des tables de référence${NC}"

# Vérifier si une table spécifique est demandée
if [ $# -eq 1 ]; then
    TABLE=$1
    echo -e "${YELLOW}Nettoyage de la table ${TABLE}...${NC}"
    php bin/console app:nettoyer:tables-reference --table=$TABLE
else
    echo -e "${YELLOW}Nettoyage de toutes les tables de référence...${NC}"
    php bin/console app:nettoyer:tables-reference
fi

if [ $? -eq 0 ]; then
    echo -e "${GREEN}Nettoyage terminé avec succès !${NC}"
else
    echo -e "${RED}Erreur lors du nettoyage.${NC}"
    exit 1
fi

echo -e "${GREEN}Les données des tables de référence ont été nettoyées et optimisées.${NC}"
echo -e "${YELLOW}Vous pouvez maintenant importer de nouvelles données sans créer de redondances.${NC}" 