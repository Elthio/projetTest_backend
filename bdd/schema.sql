CREATE TABLE LibelleCivilite (
    idLibelleCivilite INT PRIMARY KEY AUTO_INCREMENT,
    nomLibelleCivilite VARCHAR(100) NOT NULL
);

-- Nouvelle table pour le type de prospect
CREATE TABLE TypeProspect (
    idTypeProspect INT PRIMARY KEY AUTO_INCREMENT,
    nomTypeProspect VARCHAR(100) NOT NULL
);

CREATE TABLE proprio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    NumEtNomVoie VARCHAR(150) NOT NULL,
    complementAdresse VARCHAR(150),
    telephoneDomicile VARCHAR(20),
    telephonePortable VARCHAR(20),
    telephoneJob VARCHAR(20),
    code_postal VARCHAR(10) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    idLibelleCivilite INT,
    idTypeProspect INT,
    FOREIGN KEY (idLibelleCivilite) REFERENCES LibelleCivilite(idLibelleCivilite),
    FOREIGN KEY (idTypeProspect) REFERENCES TypeProspect(idTypeProspect)
);

-- Index sur la clé étrangère idLibelleCivilite
CREATE INDEX idx_proprio_civilite ON proprio(idLibelleCivilite);
CREATE INDEX idx_proprio_typeProspect ON proprio(idTypeProspect);

CREATE TABLE energie (
    idenergie INT PRIMARY KEY AUTO_INCREMENT,
    nomEnergie VARCHAR(100) NOT NULL
);

CREATE TABLE TypeVentes (
    idTypesVentes INT PRIMARY KEY AUTO_INCREMENT,
    nomTypeVente VARCHAR(100) NOT NULL
);

CREATE TABLE TypeVehicule (
    idTypeVehicule INT PRIMARY KEY AUTO_INCREMENT,
    nomTypeVehicule VARCHAR(100) NOT NULL
);

CREATE TABLE voiture (
    immatriculation VARCHAR(20) PRIMARY KEY,
    vin VARCHAR(17) UNIQUE,  -- Ajout du champ VIN
    marque VARCHAR(100) NOT NULL,
    modele VARCHAR(100) NOT NULL,
    versions VARCHAR(100) NOT NULL, 
    dateMiseEnCirculation DATE NOT NULL,
    dateAchatEtLivraison DATE NOT NULL,
    kilometrage INT NOT NULL,
    idenergie INT, 
    id_proprio INT,
    FOREIGN KEY (id_proprio) REFERENCES proprio(id),
    FOREIGN KEY (idenergie) REFERENCES energie(idenergie)
);

-- Index sur les clés étrangères
CREATE INDEX idx_voiture_energie ON voiture(idenergie);
CREATE INDEX idx_voiture_proprio ON voiture(id_proprio);

CREATE TABLE ficheVente (
    idficheVente INT PRIMARY KEY AUTO_INCREMENT,
    dateVente DATE,
    prixVente DECIMAL(10, 2),  -- Changement de type INT à DECIMAL
    idTypeVente INT,
    immatriculation VARCHAR(20),
    idTypeVehicule INT,
    numeroDossierVente VARCHAR(100),
    intermediaireVente VARCHAR(100),
    VendeurVN VARCHAR(100),
    VendeurVO VARCHAR(100),
    FOREIGN KEY (idTypeVehicule) REFERENCES TypeVehicule(idTypeVehicule),
    FOREIGN KEY (idTypeVente) REFERENCES TypeVentes(idTypesVentes),
    FOREIGN KEY (immatriculation) REFERENCES voiture(immatriculation)
);

-- Index sur les clés étrangères
CREATE INDEX idx_ficheVente_typeVente ON ficheVente(idTypeVente);
CREATE INDEX idx_ficheVente_typeVehicule ON ficheVente(idTypeVehicule);
CREATE INDEX idx_ficheVente_immatriculation ON ficheVente(immatriculation);

CREATE TABLE compteAffaire (
    idcompteAffaire VARCHAR(100) PRIMARY KEY,
    nomCompteAffaire VARCHAR(150) NOT NULL
);

CREATE TABLE compteEvenement (
    idcompteEvenement VARCHAR(100) PRIMARY KEY,
    nomCompteEvenement VARCHAR(150) NOT NULL
);

-- Renommage de la table idOrigineEvenement en OrigineEvenement
CREATE TABLE OrigineEvenement (
    idOrigineEvenement INT PRIMARY KEY AUTO_INCREMENT,
    nomOrigineEvenement VARCHAR(150) NOT NULL
);

CREATE TABLE ficheTechniqueVoiture (
    idFicheTechniqueVoiture INT PRIMARY KEY AUTO_INCREMENT,
    immatriculation VARCHAR(20),
    idenergie INT,
    idTypeVehicule INT,
    idTypeVente INT,
    idFicheVente INT,
    idcompteAffaire VARCHAR(100),
    idcompteEvenement VARCHAR(100),
    dateEvenement DATE NOT NULL,
    idOrigineEvenement INT,
    commentaireFacturation VARCHAR(255),
    FOREIGN KEY (immatriculation) REFERENCES voiture(immatriculation),
    FOREIGN KEY (idenergie) REFERENCES energie(idenergie),
    FOREIGN KEY (idTypeVehicule) REFERENCES TypeVehicule(idTypeVehicule),
    FOREIGN KEY (idTypeVente) REFERENCES TypeVentes(idTypesVentes),
    FOREIGN KEY (idFicheVente) REFERENCES ficheVente(idFicheVente),
    FOREIGN KEY (idcompteAffaire) REFERENCES compteAffaire(idcompteAffaire),
    FOREIGN KEY (idcompteEvenement) REFERENCES compteEvenement(idcompteEvenement),
    FOREIGN KEY (idOrigineEvenement) REFERENCES OrigineEvenement(idOrigineEvenement)
);

-- Index pour accélérer les requêtes sur les événements
CREATE INDEX idx_dateEvenement ON ficheTechniqueVoiture(immatriculation, dateEvenement DESC);
CREATE INDEX idx_ficheTechniqueVoiture_energie ON ficheTechniqueVoiture(idenergie);
CREATE INDEX idx_ficheTechniqueVoiture_typeVehicule ON ficheTechniqueVoiture(idTypeVehicule);
CREATE INDEX idx_ficheTechniqueVoiture_typeVente ON ficheTechniqueVoiture(idTypeVente);
CREATE INDEX idx_ficheTechniqueVoiture_ficheVente ON ficheTechniqueVoiture(idFicheVente);
CREATE INDEX idx_ficheTechniqueVoiture_compteAffaire ON ficheTechniqueVoiture(idcompteAffaire);
CREATE INDEX idx_ficheTechniqueVoiture_compteEvenement ON ficheTechniqueVoiture(idcompteEvenement);
CREATE INDEX idx_ficheTechniqueVoiture_origineEvenement ON ficheTechniqueVoiture(idOrigineEvenement);

-- Vue SQL pour obtenir le dernier événement de chaque véhicule
CREATE VIEW DernierEvenement AS
SELECT ftv.immatriculation, ftv.idcompteEvenement, ftv.dateEvenement
FROM ficheTechniqueVoiture ftv
WHERE ftv.dateEvenement = (
    SELECT MAX(ftv2.dateEvenement)
    FROM ficheTechniqueVoiture ftv2
    WHERE ftv2.immatriculation = ftv.immatriculation
);
