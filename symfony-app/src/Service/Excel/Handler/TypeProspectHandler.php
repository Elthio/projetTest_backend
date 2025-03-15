<?php

namespace App\Service\Excel\Handler;

use App\Entity\TypeProspect;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité TypeProspect
 */
class TypeProspectHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée un TypeProspect à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return TypeProspect|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?TypeProspect
    {
        $nomTypeProspect = $data['Type de prospect'] ?? null;
        
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
} 