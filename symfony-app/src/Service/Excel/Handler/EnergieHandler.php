<?php

namespace App\Service\Excel\Handler;

use App\Entity\Energie;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité Energie
 */
class EnergieHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée une Energie à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return Energie|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?Energie
    {
        $nomEnergie = $data['Libellé énergie (Energ)'] ?? null;
        
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
} 