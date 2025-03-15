#!/bin/bash

# Vérifier si un fichier a été spécifié
if [ $# -eq 0 ]; then
    echo "Usage: $0 bdd/Test-Import.xlsx"
    exit 1
fi

# Vérifier si le fichier existe
if [ ! -f "$1" ]; then
    echo "Erreur: Le fichier '$1' n'existe pas."
    exit 1
fi

# Vérifier l'extension du fichier
extension="${1##*.}"
if [ "$extension" != "xlsx" ] && [ "$extension" != "xls" ]; then
    echo "Erreur: Le fichier doit être au format Excel (.xlsx ou .xls)."
    exit 1
fi

# Exécuter la commande d'importation
php bin/console app:import:excel "$1" 