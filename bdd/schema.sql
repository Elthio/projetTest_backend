CREATE TABLE LibelleCivilite (
    idLibelleCivilite INT PRIMARY KEY AUTO_INCREMENT,
    nomLibelleCivilite VARCHAR(255) NOT NULL
);

CREATE TABLE proprio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    NumEtNomVoie VARCHAR(255) NOT NULL,
    complementAdresse VARCHAR(255),
    telephoneDomicile VARCHAR(255),
    telephonePortable VARCHAR(255),
    telephoneJob VARCHAR(255),
    code_postal VARCHAR(255) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    idLibelleCivilite INT,
    FOREIGN KEY (idLibelleCivilite) REFERENCES LibelleCivilite(idLibelleCivilite)
);

CREATE TABLE energie (
    idenergie INT PRIMARY KEY AUTO_INCREMENT,
    nomEnergie VARCHAR(255) NOT NULL
);

CREATE TABLE TypeVentes (
    idTypesVentes INT PRIMARY KEY AUTO_INCREMENT,
    nomTypeVente VARCHAR(255) NOT NULL
);

CREATE TABLE TypeVehicule (
    idTypeVehicule INT PRIMARY KEY AUTO_INCREMENT,
    nomTypeVehicule VARCHAR(255) NOT NULL
);

CREATE TABLE voiture (
    immatriculation VARCHAR(50) PRIMARY KEY,
    marque VARCHAR(255) NOT NULL,
    modele VARCHAR(255) NOT NULL,
    versions VARCHAR(255) NOT NULL, 
    dateMiseEnCirculation DATE NOT NULL,
    dateAchatEtLivraison DATE NOT NULL,
    kilometrage INT NOT NULL,
    idenergie INT, 
    id_proprio INT,
    FOREIGN KEY (id_proprio) REFERENCES proprio(id),
    FOREIGN KEY (idenergie) REFERENCES energie(idenergie)
);

CREATE TABLE ficheVente (
    idficheVente INT PRIMARY KEY AUTO_INCREMENT,
    dateVente DATE,
    prixVente INT,
    idTypeVente INT,
    immatriculation VARCHAR(50),
    idTypeVehicule INT,
    numeroDossierVente VARCHAR(255),
    intermediaireVente VARCHAR(255),
    VendeurVN VARCHAR(250),
    VendeurVO VARCHAR(250),
    FOREIGN KEY (idTypeVehicule) REFERENCES TypeVehicule(idTypeVehicule),
    FOREIGN KEY (idTypeVente) REFERENCES TypeVentes(idTypesVentes),
    FOREIGN KEY (immatriculation) REFERENCES voiture(immatriculation)
);

CREATE TABLE compteAffaire (
    idcompteAffaire VARCHAR(250) PRIMARY KEY,
    nomCompteAffaire VARCHAR(255) NOT NULL
);

CREATE TABLE compteEvenement (
    idcompteEvenement VARCHAR(250) PRIMARY KEY,
    nomCompteEvenement VARCHAR(255) NOT NULL
);

CREATE TABLE idOrigineEvenement (
    idOrigineEvenement INT PRIMARY KEY AUTO_INCREMENT,
    nomOrigineEvenement VARCHAR(255) NOT NULL
);

CREATE TABLE ficheTechniqueVoiture (
    idFicheTechniqueVoiture INT PRIMARY KEY AUTO_INCREMENT,
    immatriculation VARCHAR(50),
    idenergie INT,
    idTypeVehicule INT,
    idTypeVente INT,
    idFicheVente INT,
    idcompteAffaire VARCHAR(250),
    idcompteEvenement VARCHAR(250),
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
    FOREIGN KEY (idOrigineEvenement) REFERENCES idOrigineEvenement(idOrigineEvenement)
);

-- Index pour accélérer les requêtes sur les événements
CREATE INDEX idx_dateEvenement ON ficheTechniqueVoiture(immatriculation, dateEvenement DESC);

-- Vue SQL pour obtenir le dernier événement de chaque véhicule
CREATE VIEW DernierEvenement AS
SELECT ftv.immatriculation, ftv.idcompteEvenement, ftv.dateEvenement
FROM ficheTechniqueVoiture ftv
WHERE ftv.dateEvenement = (
    SELECT MAX(ftv2.dateEvenement)
    FROM ficheTechniqueVoiture ftv2
    WHERE ftv2.immatriculation = ftv.immatriculation
);
