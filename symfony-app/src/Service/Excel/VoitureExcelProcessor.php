<?php

namespace App\Service\Excel;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Processeur d'Excel pour les voitures
 */
class VoitureExcelProcessor extends AbstractExcelProcessor
{
    private EntityHandlerFactory $handlerFactory;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->handlerFactory = new EntityHandlerFactory($entityManager);
    }

    /**
     * Traite une ligne du fichier Excel
     * 
     * @param array $headers Les en-têtes du fichier
     * @param array $row Les données de la ligne
     */
    protected function processRow(array $headers, array $row): void
    {
        // Créer un tableau associatif avec les en-têtes
        $rowData = $this->createRowData($headers, $row);
        
        // 1. Traiter les données de référence (tables sans dépendances)
        $compteAffaire = $this->handlerFactory->getCompteAffaireHandler()->getOrCreateEntity($rowData);
        $compteEvenement = $this->handlerFactory->getCompteEvenementHandler()->getOrCreateEntity($rowData);
        $libelleCivilite = $this->handlerFactory->getLibelleCiviliteHandler()->getOrCreateEntity($rowData);
        $typeProspect = $this->handlerFactory->getTypeProspectHandler()->getOrCreateEntity($rowData);
        $energie = $this->handlerFactory->getEnergieHandler()->getOrCreateEntity($rowData);
        $typeVehicule = $this->handlerFactory->getTypeVehiculeHandler()->getOrCreateEntity($rowData);
        $typeVente = $this->handlerFactory->getTypeVenteHandler()->getOrCreateEntity($rowData);
        $origineEvenement = $this->handlerFactory->getOrigineEvenementHandler()->getOrCreateEntity($rowData);
        
        // 2. Traiter le propriétaire
        $proprio = $this->handlerFactory->getProprioHandler()->getOrCreateEntity($rowData);
        
        if ($proprio) {
            $this->handlerFactory->getProprioHandler()->associateRelatedEntities($proprio, $libelleCivilite, $typeProspect);
        }
        
        // 3. Traiter la voiture
        $voiture = $this->handlerFactory->getVoitureHandler()->getOrCreateEntity($rowData);
        
        if ($voiture) {
            $this->handlerFactory->getVoitureHandler()->associateRelatedEntities($voiture, $energie, $proprio);
        }
        
        // 4. Traiter la fiche de vente
        $ficheVente = $this->handlerFactory->getFicheVenteHandler()->getOrCreateEntity($rowData, $voiture);
        
        if ($ficheVente) {
            $this->handlerFactory->getFicheVenteHandler()->associateRelatedEntities($ficheVente, $typeVehicule, $typeVente);
        }
        
        // 5. Traiter la fiche technique
        $ficheTechniqueVoiture = $this->handlerFactory->getFicheTechniqueVoitureHandler()->getOrCreateEntity($rowData, $voiture, $compteEvenement);
        
        if ($ficheTechniqueVoiture) {
            $this->handlerFactory->getFicheTechniqueVoitureHandler()->associateRelatedEntities(
                $ficheTechniqueVoiture,
                $energie,
                $typeVehicule,
                $typeVente,
                $ficheVente,
                $compteAffaire,
                $compteEvenement,
                $origineEvenement
            );
        }
    }
} 