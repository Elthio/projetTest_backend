<?php

namespace App\Service\Excel\Handler;

use App\Entity\FicheVente;
use App\Entity\TypeVehicule;
use App\Entity\TypeVentes;
use App\Entity\Voiture;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité FicheVente
 */
class FicheVenteHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée une FicheVente à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @param Voiture|null $voiture L'entité Voiture associée
     * @return FicheVente|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data, ?Voiture $voiture = null): ?FicheVente
    {
        $numeroDossier = $data['Numéro de fiche'] ?? null;
        
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
            
            if (!empty($data['Date évènement (Veh)'])) {
                $ficheVente->setDateVente($this->parseDate($data['Date évènement (Veh)']));
            }
            
            $ficheVente->setNumeroDossierVente($data['Numéro de dossier VN VO'] ?? null);
            $ficheVente->setIntermediaireVente($data['Intermediaire de vente VN'] ?? null);
            $ficheVente->setVendeurVN($data['Vendeur VN'] ?? null);
            $ficheVente->setVendeurVO($data['Vendeur VO'] ?? null);
            $ficheVente->setVoiture($voiture);
            
            $this->entityManager->persist($ficheVente);
        }
        
        return $ficheVente;
    }
    
    /**
     * Associe les entités liées à la FicheVente
     * 
     * @param FicheVente $ficheVente L'entité FicheVente
     * @param TypeVehicule|null $typeVehicule L'entité TypeVehicule
     * @param TypeVentes|null $typeVente L'entité TypeVentes
     */
    public function associateRelatedEntities(FicheVente $ficheVente, ?TypeVehicule $typeVehicule, ?TypeVentes $typeVente): void
    {
        $ficheVente->setTypeVehicule($typeVehicule);
        $ficheVente->setTypeVente($typeVente);
    }
} 