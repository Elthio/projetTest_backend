<?php

namespace App\Service\Excel\Handler;

use App\Entity\TypeVentes;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité TypeVentes
 */
class TypeVenteHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée un TypeVentes à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return TypeVentes|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?TypeVentes
    {
        $nomTypeVente = $data['Type VN VO'] ?? null;
        
        if (empty($nomTypeVente)) {
            return null;
        }
        
        $typeVente = $this->entityManager->getRepository(TypeVentes::class)
            ->findOneBy(['nomTypeVente' => $nomTypeVente]);
        
        if (!$typeVente) {
            $typeVente = new TypeVentes();
            $typeVente->setNomTypeVente($nomTypeVente);
            $this->entityManager->persist($typeVente);
        }
        
        return $typeVente;
    }
} 