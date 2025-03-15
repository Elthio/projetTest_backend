<?php

namespace App\Service\Excel\Handler;

use App\Entity\LibelleCivilite;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité LibelleCivilite
 */
class LibelleCiviliteHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée un LibelleCivilite à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return LibelleCivilite|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?LibelleCivilite
    {
        $nomLibelleCivilite = $data['Libellé civilité'] ?? null;
        
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
} 