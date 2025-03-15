<?php

namespace App\Service\Excel\Handler;

use App\Entity\CompteAffaire;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité CompteAffaire
 */
class CompteAffaireHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée un CompteAffaire à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return CompteAffaire|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?CompteAffaire
    {
        $idCompteAffaire = $data['Compte Affaire'] ?? null;
        
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
} 