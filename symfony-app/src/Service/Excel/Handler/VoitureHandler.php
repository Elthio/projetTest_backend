<?php

namespace App\Service\Excel\Handler;

use App\Entity\Energie;
use App\Entity\Proprio;
use App\Entity\Voiture;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité Voiture
 */
class VoitureHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée une Voiture à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return Voiture|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?Voiture
    {
        $immatriculation = $data['Immatriculation'] ?? null;
        
        if (empty($immatriculation)) {
            return null;
        }
        
        $voiture = $this->entityManager->getRepository(Voiture::class)->find($immatriculation);
        
        if (!$voiture) {
            $voiture = new Voiture();
            $voiture->setImmatriculation($immatriculation);
            $voiture->setVin($data['VIN'] ?? null);
            $voiture->setMarque($data['Libellé marque (Mrq)'] ?? '');
            $voiture->setModele($data['Libellé modèle (Mod)'] ?? '');
            $voiture->setVersions($data['Version'] ?? '');
            
            if (!empty($data['Date de mise en circulation'])) {
                $voiture->setDateMiseEnCirculation($this->parseDate($data['Date de mise en circulation']));
            } else {
                $voiture->setDateMiseEnCirculation(new \DateTime());
            }
            
            if (!empty($data['Date achat (date de livraison)'])) {
                $voiture->setDateAchatEtLivraison($this->parseDate($data['Date achat (date de livraison)']));
            } else {
                $voiture->setDateAchatEtLivraison(new \DateTime());
            }
            
            $voiture->setKilometrage(intval($data['Kilométrage'] ?? 0));
            
            $this->entityManager->persist($voiture);
        }
        
        return $voiture;
    }
    
    /**
     * Associe les entités liées à la Voiture
     * 
     * @param Voiture $voiture L'entité Voiture
     * @param Energie|null $energie L'entité Energie
     * @param Proprio|null $proprio L'entité Proprio
     */
    public function associateRelatedEntities(Voiture $voiture, ?Energie $energie, ?Proprio $proprio): void
    {
        $voiture->setEnergie($energie);
        $voiture->setProprio($proprio);
    }
} 