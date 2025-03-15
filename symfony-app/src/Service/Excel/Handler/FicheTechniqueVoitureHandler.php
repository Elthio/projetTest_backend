<?php

namespace App\Service\Excel\Handler;

use App\Entity\CompteAffaire;
use App\Entity\CompteEvenement;
use App\Entity\Energie;
use App\Entity\FicheVente;
use App\Entity\FicheTechniqueVoiture;
use App\Entity\OrigineEvenement;
use App\Entity\TypeVehicule;
use App\Entity\TypeVentes;
use App\Entity\Voiture;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité FicheTechniqueVoiture
 */
class FicheTechniqueVoitureHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée une FicheTechniqueVoiture à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @param Voiture|null $voiture L'entité Voiture associée
     * @param CompteEvenement|null $compteEvenement L'entité CompteEvenement associée
     * @return FicheTechniqueVoiture|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data, ?Voiture $voiture = null, ?CompteEvenement $compteEvenement = null): ?FicheTechniqueVoiture
    {
        if (!$voiture || empty($data['Date évènement (Veh)'])) {
            return null;
        }
        
        $date = $this->parseDate($data['Date évènement (Veh)']);
        
        $ficheTechniqueVoiture = $this->entityManager->getRepository(FicheTechniqueVoiture::class)
            ->findOneBy([
                'voiture' => $voiture,
                'dateEvenement' => $date,
                'compteEvenement' => $compteEvenement
            ]);
        
        if (!$ficheTechniqueVoiture) {
            $ficheTechniqueVoiture = new FicheTechniqueVoiture();
            $ficheTechniqueVoiture->setVoiture($voiture);
            $ficheTechniqueVoiture->setDateEvenement($date);
            $ficheTechniqueVoiture->setCommentaireFacturation($data['Commentaire de facturation (Veh)'] ?? null);
            
            $this->entityManager->persist($ficheTechniqueVoiture);
        }
        
        return $ficheTechniqueVoiture;
    }
    
    /**
     * Associe les entités liées à la FicheTechniqueVoiture
     * 
     * @param FicheTechniqueVoiture $ficheTechniqueVoiture L'entité FicheTechniqueVoiture
     * @param Energie|null $energie L'entité Energie
     * @param TypeVehicule|null $typeVehicule L'entité TypeVehicule
     * @param TypeVentes|null $typeVente L'entité TypeVentes
     * @param FicheVente|null $ficheVente L'entité FicheVente
     * @param CompteAffaire|null $compteAffaire L'entité CompteAffaire
     * @param CompteEvenement|null $compteEvenement L'entité CompteEvenement
     * @param OrigineEvenement|null $origineEvenement L'entité OrigineEvenement
     */
    public function associateRelatedEntities(
        FicheTechniqueVoiture $ficheTechniqueVoiture,
        ?Energie $energie,
        ?TypeVehicule $typeVehicule,
        ?TypeVentes $typeVente,
        ?FicheVente $ficheVente,
        ?CompteAffaire $compteAffaire,
        ?CompteEvenement $compteEvenement,
        ?OrigineEvenement $origineEvenement
    ): void {
        $ficheTechniqueVoiture->setEnergie($energie);
        $ficheTechniqueVoiture->setTypeVehicule($typeVehicule);
        $ficheTechniqueVoiture->setTypeVente($typeVente);
        $ficheTechniqueVoiture->setFicheVente($ficheVente);
        $ficheTechniqueVoiture->setCompteAffaire($compteAffaire);
        $ficheTechniqueVoiture->setCompteEvenement($compteEvenement);
        $ficheTechniqueVoiture->setOrigineEvenement($origineEvenement);
    }
} 