#!/bin/bash

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}Script de nettoyage des données de la table Energie${NC}"

# Exécuter la commande de nettoyage
echo -e "${YELLOW}Exécution de la commande de nettoyage...${NC}"
php bin/console app:nettoyer:energies

if [ $? -eq 0 ]; then
    echo -e "${GREEN}Nettoyage terminé avec succès !${NC}"
else
    echo -e "${RED}Erreur lors du nettoyage.${NC}"
    exit 1
fi

echo -e "${GREEN}Les données de la table Energie ont été nettoyées et optimisées.${NC}"
echo -e "${YELLOW}Vous pouvez maintenant importer de nouvelles données sans créer de redondances.${NC}" 