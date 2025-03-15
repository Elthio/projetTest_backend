<?php

namespace App\Service;

use App\Entity\CompteAffaire;
use App\Entity\CompteEvenement;
use App\Entity\Energie;
use App\Entity\FicheVente;
use App\Entity\FicheTechniqueVoiture;
use App\Entity\LibelleCivilite;
use App\Entity\OrigineEvenement;
use App\Entity\Proprio;
use App\Entity\TypeProspect;
use App\Entity\TypeVehicule;
use App\Entity\TypeVentes;
use App\Entity\Voiture;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use \DateTime;
use App\Service\Excel\VoitureExcelProcessor;

/**
 * Service d'importation Excel
 */
class ExcelImportService
{
    private EntityManagerInterface $entityManager;
    private VoitureExcelProcessor $voitureProcessor;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->voitureProcessor = new VoitureExcelProcessor($entityManager);
    }

    /**
     * Importe les données d'un fichier Excel
     * 
     * @param UploadedFile $file Le fichier Excel à importer
     * @return array Statistiques d'importation
     */
    public function importExcelFile(UploadedFile $file): array
    {
        return $this->voitureProcessor->processFile($file);
    }
    
    /**
     * Traite une ligne du fichier Excel
     * 
     * @param array $headers Les en-têtes du fichier
     * @param array $row Les données de la ligne
     */
    private function processRow(array $headers, array $row): void
    {
        // Créer un tableau associatif avec les en-têtes
        $rowData = [];
        foreach ($headers as $index => $header) {
            if (isset($row[$index])) {
                $rowData[$header] = $row[$index];
            } else {
                $rowData[$header] = null;
            }
        }
        
        // Traiter les données dans l'ordre des dépendances
        
        // 1. Traiter les données de référence (tables sans dépendances)
        $compteAffaire = $this->getOrCreateCompteAffaire($rowData['Compte Affaire'] ?? null);
        $compteEvenement = $this->getOrCreateCompteEvenement($rowData['Compte évènement (Veh)'] ?? null);
        $libelleCivilite = $this->getOrCreateLibelleCivilite($rowData['Libellé civilité'] ?? null);
        $typeProspect = $this->getOrCreateTypeProspect($rowData['Type de prospect'] ?? null);
        $energie = $this->getOrCreateEnergie($rowData['Libellé énergie (Energ)'] ?? null);
        $typeVehicule = $this->getOrCreateTypeVehicule($rowData['Type VN VO'] ?? null);
        $typeVente = $this->getOrCreateTypeVente($rowData['Type VN VO'] ?? null);
        $origineEvenement = $this->getOrCreateOrigineEvenement($rowData['Origine évènement (Veh)'] ?? null);
        
        // 2. Traiter le propriétaire
        $proprio = $this->getOrCreateProprio(
            $rowData['Nom'] ?? null,
            $rowData['Prénom'] ?? null,
            $rowData['Email'] ?? null,
            $rowData['N° et Nom de la voie'] ?? null,
            $rowData['Complément adresse 1'] ?? null,
            $rowData['Code postal'] ?? null,
            $rowData['Ville'] ?? null,
            $rowData['Téléphone domicile'] ?? null,
            $rowData['Téléphone portable'] ?? null,
            $rowData['Téléphone job'] ?? null,
            $libelleCivilite,
            $typeProspect
        );
        
        // 3. Traiter la voiture
        $voiture = $this->getOrCreateVoiture(
            $rowData['Immatriculation'] ?? null,
            $rowData['VIN'] ?? null,
            $rowData['Libellé marque (Mrq)'] ?? null,
            $rowData['Libellé modèle (Mod)'] ?? null,
            $rowData['Version'] ?? null,
            $rowData['Date de mise en circulation'] ?? null,
            $rowData['Date achat (date de livraison)'] ?? null,
            $rowData['Kilométrage'] ?? null,
            $energie,
            $proprio
        );
        
        // 4. Traiter la fiche de vente
        $ficheVente = $this->getOrCreateFicheVente(
            $rowData['Numéro de fiche'] ?? null,
            $rowData['Date évènement (Veh)'] ?? null,
            $rowData['Numéro de dossier VN VO'] ?? null,
            $rowData['Intermediaire de vente VN'] ?? null,
            $rowData['Vendeur VN'] ?? null,
            $rowData['Vendeur VO'] ?? null,
            $voiture,
            $typeVehicule,
            $typeVente
        );
        
        // 5. Traiter la fiche technique
        $this->getOrCreateFicheTechniqueVoiture(
            $voiture,
            $energie,
            $typeVehicule,
            $typeVente,
            $ficheVente,
            $compteAffaire,
            $compteEvenement,
            $rowData['Date évènement (Veh)'] ?? null,
            $origineEvenement,
            $rowData['Commentaire de facturation (Veh)'] ?? null
        );
    }
    
    /**
     * Récupère ou crée un compte affaire
     */
    private function getOrCreateCompteAffaire(?string $idCompteAffaire): ?CompteAffaire
    {
        if (empty($idCompteAffaire)) {
            return null;
        }
        
        $compteAffaire = $this->entityManager->getRepository(CompteAffaire::class)->find($idCompteAffaire);
        
        if (!$compteAffaire) {
            $compteAffaire = new CompteAffaire();
            $compteAffaire->setIdcompteAffaire($idCompteAffaire);
            $compteAffaire->setNomCompteAffaire($idCompteAffaire);
            $this->entityManager->persist($compteAffaire);
        }
        
        return $compteAffaire;
    }
    
    /**
     * Récupère ou crée un compte événement
     */
    private function getOrCreateCompteEvenement(?string $idCompteEvenement): ?CompteEvenement
    {
        if (empty($idCompteEvenement)) {
            return null;
        }
        
        $compteEvenement = $this->entityManager->getRepository(CompteEvenement::class)->find($idCompteEvenement);
        
        if (!$compteEvenement) {
            $compteEvenement = new CompteEvenement();
            $compteEvenement->setIdcompteEvenement($idCompteEvenement);
            $compteEvenement->setNomCompteEvenement($idCompteEvenement);
            $this->entityManager->persist($compteEvenement);
        }
        
        return $compteEvenement;
    }
    
    /**
     * Récupère ou crée un libellé civilité
     */
    private function getOrCreateLibelleCivilite(?string $nomLibelleCivilite): ?LibelleCivilite
    {
        if (empty($nomLibelleCivilite)) {
            return null;
        }
        
        $libelleCivilite = $this->entityManager->getRepository(LibelleCivilite::class)
            ->findOneBy(['nomLibelleCivilite' => $nomLibelleCivilite]);
        
        if (!$libelleCivilite) {
            $libelleCivilite = new LibelleCivilite();
            $libelleCivilite->setNomLibelleCivilite($nomLibelleCivilite);
            $this->entityManager->persist($libelleCivilite);
        }
        
        return $libelleCivilite;
    }
    
    /**
     * Récupère ou crée un type de prospect
     */
    private function getOrCreateTypeProspect(?string $nomTypeProspect): ?TypeProspect
    {
        if (empty($nomTypeProspect)) {
            return null;
        }
        
        $typeProspect = $this->entityManager->getRepository(TypeProspect::class)
            ->findOneBy(['nomTypeProspect' => $nomTypeProspect]);
        
        if (!$typeProspect) {
            $typeProspect = new TypeProspect();
            $typeProspect->setNomTypeProspect($nomTypeProspect);
            $this->entityManager->persist($typeProspect);
        }
        
        return $typeProspect;
    }
    
    /**
     * Récupère ou crée une énergie
     */
    private function getOrCreateEnergie(?string $nomEnergie): ?Energie
    {
        if (empty($nomEnergie)) {
            return null;
        }
        
        $energie = $this->entityManager->getRepository(Energie::class)
            ->findOneBy(['nomEnergie' => $nomEnergie]);
        
        if (!$energie) {
            $energie = new Energie();
            $energie->setNomEnergie($nomEnergie);
            $this->entityManager->persist($energie);
        }
        
        return $energie;
    }
    
    /**
     * Récupère ou crée un type de véhicule
     */
    private function getOrCreateTypeVehicule(?string $nomTypeVehicule): ?TypeVehicule
    {
        if (empty($nomTypeVehicule)) {
            return null;
        }
        
        $typeVehicule = $this->entityManager->getRepository(TypeVehicule::class)
            ->findOneBy(['nomTypeVehicule' => $nomTypeVehicule]);
        
        if (!$typeVehicule) {
            $typeVehicule = new TypeVehicule();
            $typeVehicule->setNomTypeVehicule($nomTypeVehicule);
            $this->entityManager->persist($typeVehicule);
        }
        
        return $typeVehicule;
    }
    
    /**
     * Récupère ou crée un type de vente
     */
    private function getOrCreateTypeVente(?string $nomTypeVente): ?TypeVentes
    {
        if (empty($nomTypeVente)) {
            return null;
        }
        
        $typeVente = $this->entityManager->getRepository(TypeVentes::class)
            ->findOneBy(['nomTypeVente' => $nomTypeVente]);
        
        if (!$typeVente) {
            $typeVente = new TypeVentes();
            $typeVente->setNomTypeVente($nomTypeVente);
            $this->entityManager->persist($typeVente);
        }
        
        return $typeVente;
    }
    
    /**
     * Récupère ou crée une origine d'événement
     */
    private function getOrCreateOrigineEvenement(?string $nomOrigineEvenement): ?OrigineEvenement
    {
        if (empty($nomOrigineEvenement)) {
            return null;
        }
        
        $origineEvenement = $this->entityManager->getRepository(OrigineEvenement::class)
            ->findOneBy(['nomOrigineEvenement' => $nomOrigineEvenement]);
        
        if (!$origineEvenement) {
            $origineEvenement = new OrigineEvenement();
            $origineEvenement->setNomOrigineEvenement($nomOrigineEvenement);
            $this->entityManager->persist($origineEvenement);
        }
        
        return $origineEvenement;
    }
    
    /**
     * Récupère ou crée un propriétaire
     */
    private function getOrCreateProprio(
        ?string $nom,
        ?string $prenom,
        ?string $email,
        ?string $numEtNomVoie,
        ?string $complementAdresse,
        ?string $codePostal,
        ?string $ville,
        ?string $telephoneDomicile,
        ?string $telephonePortable,
        ?string $telephoneJob,
        ?LibelleCivilite $libelleCivilite,
        ?TypeProspect $typeProspect
    ): ?Proprio {
        if (empty($nom) || empty($prenom)) {
            return null;
        }
        
        $proprio = $this->entityManager->getRepository(Proprio::class)
            ->findOneBy([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email
            ]);
        
        if (!$proprio) {
            $proprio = new Proprio();
            $proprio->setNom($nom);
            $proprio->setPrenom($prenom);
            $proprio->setEmail($email ?? '');
            $proprio->setNumEtNomVoie($numEtNomVoie ?? '');
            $proprio->setComplementAdresse($complementAdresse);
            $proprio->setCodePostal($codePostal ?? '');
            $proprio->setVille($ville ?? '');
            $proprio->setTelephoneDomicile($telephoneDomicile);
            $proprio->setTelephonePortable($telephonePortable);
            $proprio->setTelephoneJob($telephoneJob);
            $proprio->setLibelleCivilite($libelleCivilite);
            $proprio->setTypeProspect($typeProspect);
            $this->entityManager->persist($proprio);
        }
        
        return $proprio;
    }
    
    /**
     * Récupère ou crée une voiture
     */
    private function getOrCreateVoiture(
        ?string $immatriculation,
        ?string $vin,
        ?string $marque,
        ?string $modele,
        ?string $versions,
        ?string $dateMiseEnCirculation,
        ?string $dateAchatEtLivraison,
        ?string $kilometrage,
        ?Energie $energie,
        ?Proprio $proprio
    ): ?Voiture {
        if (empty($immatriculation)) {
            return null;
        }
        
        $voiture = $this->entityManager->getRepository(Voiture::class)->find($immatriculation);
        
        if (!$voiture) {
            $voiture = new Voiture();
            $voiture->setImmatriculation($immatriculation);
            $voiture->setVin($vin);
            $voiture->setMarque($marque ?? '');
            $voiture->setModele($modele ?? '');
            $voiture->setVersions($versions ?? '');
            
            if (!empty($dateMiseEnCirculation)) {
                $voiture->setDateMiseEnCirculation($this->parseDate($dateMiseEnCirculation));
            } else {
                $voiture->setDateMiseEnCirculation(new DateTime());
            }
            
            if (!empty($dateAchatEtLivraison)) {
                $voiture->setDateAchatEtLivraison($this->parseDate($dateAchatEtLivraison));
            } else {
                $voiture->setDateAchatEtLivraison(new DateTime());
            }
            
            $voiture->setKilometrage(intval($kilometrage ?? 0));
            $voiture->setEnergie($energie);
            $voiture->setProprio($proprio);
            
            $this->entityManager->persist($voiture);
        }
        
        return $voiture;
    }
    
    /**
     * Récupère ou crée une fiche de vente
     */
    private function getOrCreateFicheVente(
        ?string $numeroDossier,
        ?string $dateVente,
        ?string $numeroDossierVente,
        ?string $intermediaireVente,
        ?string $vendeurVN,
        ?string $vendeurVO,
        ?Voiture $voiture,
        ?TypeVehicule $typeVehicule,
        ?TypeVentes $typeVente
    ): ?FicheVente {
        if (empty($numeroDossier) || !$voiture) {
            return null;
        }
        
        $ficheVente = $this->entityManager->getRepository(FicheVente::class)
            ->findOneBy([
                'numeroDossierVente' => $numeroDossier,
                'voiture' => $voiture
            ]);
        
        if (!$ficheVente) {
            $ficheVente = new FicheVente();
            
            if (!empty($dateVente)) {
                $ficheVente->setDateVente($this->parseDate($dateVente));
            }
            
            $ficheVente->setNumeroDossierVente($numeroDossierVente);
            $ficheVente->setIntermediaireVente($intermediaireVente);
            $ficheVente->setVendeurVN($vendeurVN);
            $ficheVente->setVendeurVO($vendeurVO);
            $ficheVente->setVoiture($voiture);
            $ficheVente->setTypeVehicule($typeVehicule);
            $ficheVente->setTypeVente($typeVente);
            
            $this->entityManager->persist($ficheVente);
        }
        
        return $ficheVente;
    }
    
    /**
     * Récupère ou crée une fiche technique de voiture
     */
    private function getOrCreateFicheTechniqueVoiture(
        ?Voiture $voiture,
        ?Energie $energie,
        ?TypeVehicule $typeVehicule,
        ?TypeVentes $typeVente,
        ?FicheVente $ficheVente,
        ?CompteAffaire $compteAffaire,
        ?CompteEvenement $compteEvenement,
        ?string $dateEvenement,
        ?OrigineEvenement $origineEvenement,
        ?string $commentaireFacturation
    ): ?FicheTechniqueVoiture {
        if (!$voiture || empty($dateEvenement)) {
            return null;
        }
        
        $date = $this->parseDate($dateEvenement);
        
        $ficheTechniqueVoiture = $this->entityManager->getRepository(FicheTechniqueVoiture::class)
            ->findOneBy([
                'voiture' => $voiture,
                'dateEvenement' => $date,
                'compteEvenement' => $compteEvenement
            ]);
        
        if (!$ficheTechniqueVoiture) {
            $ficheTechniqueVoiture = new FicheTechniqueVoiture();
            $ficheTechniqueVoiture->setVoiture($voiture);
            $ficheTechniqueVoiture->setEnergie($energie);
            $ficheTechniqueVoiture->setTypeVehicule($typeVehicule);
            $ficheTechniqueVoiture->setTypeVente($typeVente);
            $ficheTechniqueVoiture->setFicheVente($ficheVente);
            $ficheTechniqueVoiture->setCompteAffaire($compteAffaire);
            $ficheTechniqueVoiture->setCompteEvenement($compteEvenement);
            $ficheTechniqueVoiture->setDateEvenement($date);
            $ficheTechniqueVoiture->setOrigineEvenement($origineEvenement);
            $ficheTechniqueVoiture->setCommentaireFacturation($commentaireFacturation);
            
            $this->entityManager->persist($ficheTechniqueVoiture);
        }
        
        return $ficheTechniqueVoiture;
    }
    
    /**
     * Parse une date à partir d'une chaîne
     */
    private function parseDate(?string $dateString): DateTime
    {
        if (empty($dateString)) {
            return new DateTime();
        }
        
        try {
            // Essayer différents formats de date
            $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'Y/m/d'];
            
            foreach ($formats as $format) {
                $date = DateTime::createFromFormat($format, $dateString);
                if ($date !== false) {
                    return $date;
                }
            }
            
            // Si aucun format ne correspond, essayer de parser la date
            return new DateTime($dateString);
        } catch (\Exception $e) {
            return new DateTime();
        }
    }
} 