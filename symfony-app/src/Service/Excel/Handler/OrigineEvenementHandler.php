<?php

namespace App\Service\Excel\Handler;

use App\Entity\OrigineEvenement;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité OrigineEvenement
 */
class OrigineEvenementHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée un OrigineEvenement à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return OrigineEvenement|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?OrigineEvenement
    {
        $nomOrigineEvenement = $data['Origine évènement (Veh)'] ?? null;
        
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
} 