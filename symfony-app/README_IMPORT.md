# Guide d'importation de données Excel

Ce document explique comment importer des données depuis un fichier Excel vers la base de données de l'application.

## Architecture du système d'importation

Le système d'importation est conçu avec une architecture orientée objet qui facilite la maintenance et l'extension. Voici les principaux composants :

### 1. Classes abstraites et interfaces

- `AbstractExcelProcessor` : Classe abstraite pour le traitement des fichiers Excel
- `EntityHandlerInterface` : Interface pour les gestionnaires d'entités
- `AbstractEntityHandler` : Classe abstraite pour les gestionnaires d'entités

### 2. Gestionnaires d'entités

Chaque entité a son propre gestionnaire qui implémente la logique de création ou de récupération :

- `CompteAffaireHandler`
- `CompteEvenementHandler`
- `EnergieHandler`
- `FicheVenteHandler`
- `FicheTechniqueVoitureHandler`
- `LibelleCiviliteHandler`
- `OrigineEvenementHandler`
- `ProprioHandler`
- `TypeProspectHandler`
- `TypeVehiculeHandler`
- `TypeVenteHandler`
- `VoitureHandler`

### 3. Fabrique de gestionnaires

- `EntityHandlerFactory` : Fabrique qui crée et gère les instances des gestionnaires d'entités

### 4. Processeurs spécifiques

- `VoitureExcelProcessor` : Processeur spécifique pour l'importation des données de voitures

### 5. Service principal

- `ExcelImportService` : Service principal qui coordonne l'importation

## Prérequis

- PHP 7.4 ou supérieur
- Symfony 5.4 ou supérieur
- Extension PHP `zip` installée
- Bibliothèque PhpSpreadsheet installée (`composer require phpoffice/phpspreadsheet`)

## Format du fichier Excel

Le fichier Excel doit respecter les règles suivantes :

1. La première ligne doit contenir les en-têtes des colonnes
2. Les en-têtes doivent correspondre aux noms des champs attendus par l'application
3. Le fichier doit être au format `.xlsx` ou `.xls`

### Colonnes attendues

Voici les colonnes attendues dans le fichier Excel :

| Colonne | Description | Obligatoire |
|---------|-------------|-------------|
| Compte Affaire | Identifiant du compte affaire | Non |
| Compte évènement (Veh) | Identifiant du compte événement | Non |
| Compte dernier évènement (Veh) | Identifiant du dernier compte événement | Non |
| Numéro de fiche | Numéro de la fiche de vente | Non |
| Libellé civilité | Civilité du propriétaire (M., Mme, etc.) | Non |
| Propriétaire actuel du véhicule | Propriétaire actuel | Non |
| Nom | Nom du propriétaire | Oui |
| Prénom | Prénom du propriétaire | Oui |
| Email | Email du propriétaire | Non |
| N° et Nom de la voie | Adresse du propriétaire | Non |
| Complément adresse 1 | Complément d'adresse | Non |
| Code postal | Code postal | Non |
| Ville | Ville | Non |
| Téléphone domicile | Téléphone domicile | Non |
| Téléphone portable | Téléphone portable | Non |
| Téléphone job | Téléphone professionnel | Non |
| Date de mise en circulation | Date de mise en circulation | Non |
| Date achat (date de livraison) | Date d'achat | Non |
| Date dernier évènement (Veh) | Date du dernier événement | Non |
| Libellé marque (Mrq) | Marque du véhicule | Non |
| Libellé modèle (Mod) | Modèle du véhicule | Non |
| Version | Version du véhicule | Non |
| VIN | Numéro d'identification du véhicule | Non |
| Immatriculation | Immatriculation du véhicule | Oui |
| Type de prospect | Type de prospect | Non |
| Kilométrage | Kilométrage du véhicule | Non |
| Libellé énergie (Energ) | Type d'énergie du véhicule | Non |
| Vendeur VN | Vendeur véhicule neuf | Non |
| Vendeur VO | Vendeur véhicule occasion | Non |
| Commentaire de facturation (Veh) | Commentaire de facturation | Non |
| Type VN VO | Type de véhicule (Neuf/Occasion) | Non |
| Numéro de dossier VN VO | Numéro de dossier de vente | Non |
| Intermediaire de vente VN | Intermédiaire de vente | Non |
| Date évènement (Veh) | Date de l'événement | Non |
| Origine évènement (Veh) | Origine de l'événement | Non |

## Méthodes d'importation

### 1. Via l'interface web

1. Connectez-vous à l'application
2. Accédez à la page "Importation Excel" depuis le menu de navigation
3. Cliquez sur "Parcourir" et sélectionnez votre fichier Excel
4. Cliquez sur "Importer"
5. Attendez que l'importation se termine
6. Consultez le rapport d'importation affiché à l'écran

### 2. Via la ligne de commande

Vous pouvez utiliser le script d'importation fourni :

```bash
./import-excel.sh chemin/vers/fichier.xlsx
```

Ou utiliser directement la commande Symfony :

```bash
php bin/console app:import:excel chemin/vers/fichier.xlsx
```

## Gestion des erreurs

Lors de l'importation, les erreurs sont enregistrées et affichées à la fin du processus. Les erreurs n'empêchent pas l'importation des autres lignes du fichier.

Types d'erreurs courants :
- Données manquantes pour les champs obligatoires
- Format de date incorrect
- Références à des entités inexistantes

## Comportement d'importation

- Si une entité existe déjà (basée sur ses identifiants uniques), elle ne sera pas dupliquée
- Les données existantes ne sont pas écrasées par l'importation
- Les nouvelles données sont ajoutées à la base de données

## Extension du système

Pour ajouter le support d'un nouveau type d'entité :

1. Créez un nouveau gestionnaire d'entité qui étend `AbstractEntityHandler`
2. Ajoutez une méthode dans `EntityHandlerFactory` pour récupérer ce gestionnaire
3. Mettez à jour le processeur approprié pour utiliser ce gestionnaire

## Support

En cas de problème avec l'importation, veuillez contacter l'administrateur système. 