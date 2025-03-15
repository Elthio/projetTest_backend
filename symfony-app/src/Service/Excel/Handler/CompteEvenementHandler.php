<?php

namespace App\Service\Excel\Handler;

use App\Entity\CompteEvenement;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité CompteEvenement
 */
class CompteEvenementHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée un CompteEvenement à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return CompteEvenement|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?CompteEvenement
    {
        $idCompteEvenement = $data['Compte évènement (Veh)'] ?? null;
        
        if (empty($idCompteEvenement)) {
            // Essayer avec le compte dernier événement si disponible
            $idCompteEvenement = $data['Compte dernier évènement (Veh)'] ?? null;
            
            if (empty($idCompteEvenement)) {
                return null;
            }
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
} 