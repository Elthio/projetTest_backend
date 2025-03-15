<?php

namespace App\Service\Excel\Handler;

use App\Entity\TypeVehicule;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité TypeVehicule
 */
class TypeVehiculeHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée un TypeVehicule à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return TypeVehicule|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?TypeVehicule
    {
        $nomTypeVehicule = $data['Type VN VO'] ?? null;
        
        if (empty($nomTypeVehicule)) {
            return null;
        }
        
        $typeVehicule = $this->entityManager->getRepository(TypeVehicule::class)
            ->findOneBy(['nomTypeVehicule' => $nomTypeVehicule]);
        
        if (!$typeVehicule) {
            $typeVehicule = new TypeVehicule();
            $typeVehicule->setNomTypeVehicule($nomTypeVehicule);
            $this->entityManager->persist($typeVehicule);
        }
        
        return $typeVehicule;
    }
} 