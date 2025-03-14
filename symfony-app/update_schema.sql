-- Désactiver les contraintes de clé étrangère
SET FOREIGN_KEY_CHECKS=0;

-- Supprimer les contraintes de clé étrangère qui font référence à voiture_immatriculation
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'voitures' AND CONSTRAINT_NAME = 'FK_F20AC0737DF2B86C');
SET @sql = IF(@constraint_exists > 0, 'ALTER TABLE fiche_vente DROP FOREIGN KEY FK_F20AC0737DF2B86C', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'voitures' AND CONSTRAINT_NAME = 'FK_D17DDC617DF2B86C');
SET @sql = IF(@constraint_exists > 0, 'ALTER TABLE fiche_technique_voiture DROP FOREIGN KEY FK_D17DDC617DF2B86C', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Supprimer les autres contraintes de clé étrangère
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'voitures' AND CONSTRAINT_NAME = 'FK_D17DDC6163FAAB74');
SET @sql = IF(@constraint_exists > 0, 'ALTER TABLE fiche_technique_voiture DROP FOREIGN KEY FK_D17DDC6163FAAB74', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'voitures' AND CONSTRAINT_NAME = 'FK_D17DDC61389638EB');
SET @sql = IF(@constraint_exists > 0, 'ALTER TABLE fiche_technique_voiture DROP FOREIGN KEY FK_D17DDC61389638EB', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'voitures' AND CONSTRAINT_NAME = 'FK_D17DDC6157D733D');
SET @sql = IF(@constraint_exists > 0, 'ALTER TABLE fiche_technique_voiture DROP FOREIGN KEY FK_D17DDC6157D733D', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Mettre à jour les tables
ALTER TABLE compte_evenement CHANGE idcompte_evenement idcompte_evenement VARCHAR(100) NOT NULL, CHANGE nom_compte_evenement nom_compte_evenement VARCHAR(150) NOT NULL;
ALTER TABLE compte_affaire CHANGE idcompte_affaire idcompte_affaire VARCHAR(100) NOT NULL, CHANGE nom_compte_affaire nom_compte_affaire VARCHAR(150) NOT NULL;

-- Créer la nouvelle table OrigineEvenement
CREATE TABLE IF NOT EXISTS OrigineEvenement (
    idOrigineEvenement INT AUTO_INCREMENT NOT NULL, 
    nom_origine_evenement VARCHAR(150) NOT NULL, 
    PRIMARY KEY(idOrigineEvenement)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Vérifier si la table origine_evenement existe avant de copier les données
SET @table_exists = (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'voitures' AND TABLE_NAME = 'origine_evenement');
SET @sql = IF(@table_exists > 0, 'INSERT INTO OrigineEvenement (idOrigineEvenement, nom_origine_evenement) SELECT idOrigineEvenement, nomOrigineEvenement FROM origine_evenement', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Créer la table TypeProspect
CREATE TABLE IF NOT EXISTS type_prospect (
    idTypeProspect INT AUTO_INCREMENT NOT NULL, 
    nom_type_prospect VARCHAR(100) NOT NULL, 
    PRIMARY KEY(idTypeProspect)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Mettre à jour les autres tables
ALTER TABLE fiche_vente CHANGE voiture_immatriculation voiture_immatriculation VARCHAR(20) DEFAULT NULL, CHANGE prix_vente prix_vente NUMERIC(10, 2) DEFAULT NULL, CHANGE numero_dossier_vente numero_dossier_vente VARCHAR(100) DEFAULT NULL, CHANGE intermediaire_vente intermediaire_vente VARCHAR(100) DEFAULT NULL, CHANGE vendeur_vn vendeur_vn VARCHAR(100) DEFAULT NULL, CHANGE vendeur_vo vendeur_vo VARCHAR(100) DEFAULT NULL;

-- Vérifier si la colonne idTypeProspect existe déjà dans la table proprio
SET @column_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'voitures' AND TABLE_NAME = 'proprio' AND COLUMN_NAME = 'idTypeProspect');
SET @sql = IF(@column_exists = 0, 'ALTER TABLE proprio ADD idTypeProspect INT DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE proprio CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE email email VARCHAR(150) NOT NULL, CHANGE num_et_nom_voie num_et_nom_voie VARCHAR(150) NOT NULL, CHANGE complement_adresse complement_adresse VARCHAR(150) DEFAULT NULL, CHANGE telephone_domicile telephone_domicile VARCHAR(20) DEFAULT NULL, CHANGE telephone_portable telephone_portable VARCHAR(20) DEFAULT NULL, CHANGE telephone_job telephone_job VARCHAR(20) DEFAULT NULL, CHANGE code_postal code_postal VARCHAR(10) NOT NULL, CHANGE ville ville VARCHAR(100) NOT NULL;

-- Vérifier si la colonne vin existe déjà dans la table voiture
SET @column_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'voitures' AND TABLE_NAME = 'voiture' AND COLUMN_NAME = 'vin');
SET @sql = IF(@column_exists = 0, 'ALTER TABLE voiture ADD vin VARCHAR(17) DEFAULT NULL', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE voiture CHANGE immatriculation immatriculation VARCHAR(20) NOT NULL, CHANGE marque marque VARCHAR(100) NOT NULL, CHANGE modele modele VARCHAR(100) NOT NULL, CHANGE versions versions VARCHAR(100) NOT NULL;

-- Vérifier si l'index existe déjà
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = 'voitures' AND TABLE_NAME = 'voiture' AND INDEX_NAME = 'UNIQ_E9E2810FB1085141');
SET @sql = IF(@index_exists = 0, 'CREATE UNIQUE INDEX UNIQ_E9E2810FB1085141 ON voiture (vin)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE fiche_technique_voiture CHANGE voiture_immatriculation voiture_immatriculation VARCHAR(20) DEFAULT NULL, CHANGE compte_affaire_id compte_affaire_id VARCHAR(100) DEFAULT NULL, CHANGE compte_evenement_id compte_evenement_id VARCHAR(100) DEFAULT NULL;

ALTER TABLE libelle_civilite CHANGE nom_libelle_civilite nom_libelle_civilite VARCHAR(100) NOT NULL;

-- Vérifier si la contrainte existe déjà
SET @constraint_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = 'voitures' AND CONSTRAINT_NAME = 'FK_79F4F386324FC452');
SET @sql = IF(@constraint_exists = 0, 'ALTER TABLE proprio ADD CONSTRAINT FK_79F4F386324FC452 FOREIGN KEY (idTypeProspect) REFERENCES type_prospect (idTypeProspect)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Vérifier si l'index existe déjà
SET @index_exists = (SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = 'voitures' AND TABLE_NAME = 'proprio' AND INDEX_NAME = 'IDX_79F4F386324FC452');
SET @sql = IF(@index_exists = 0, 'CREATE INDEX IDX_79F4F386324FC452 ON proprio (idTypeProspect)', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Ajouter les nouvelles contraintes de clé étrangère
ALTER TABLE fiche_technique_voiture ADD CONSTRAINT FK_D17DDC617DF2B86C FOREIGN KEY (voiture_immatriculation) REFERENCES voiture (immatriculation);
ALTER TABLE fiche_technique_voiture ADD CONSTRAINT FK_D17DDC6163FAAB74 FOREIGN KEY (compte_evenement_id) REFERENCES compte_evenement (idcompte_evenement);
ALTER TABLE fiche_technique_voiture ADD CONSTRAINT FK_D17DDC61389638EB FOREIGN KEY (compte_affaire_id) REFERENCES compte_affaire (idcompte_affaire);
ALTER TABLE fiche_technique_voiture ADD CONSTRAINT FK_D17DDC6157D733D FOREIGN KEY (origine_evenement_id) REFERENCES OrigineEvenement (idOrigineEvenement);
ALTER TABLE fiche_vente ADD CONSTRAINT FK_F20AC0737DF2B86C FOREIGN KEY (voiture_immatriculation) REFERENCES voiture (immatriculation);

-- Supprimer l'ancienne table si elle existe
SET @table_exists = (SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'voitures' AND TABLE_NAME = 'origine_evenement');
SET @sql = IF(@table_exists > 0, 'DROP TABLE origine_evenement', 'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Réactiver les contraintes de clé étrangère
SET FOREIGN_KEY_CHECKS=1; 