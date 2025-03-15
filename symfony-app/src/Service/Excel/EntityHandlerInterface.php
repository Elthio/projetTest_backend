<?php

namespace App\Service\Excel;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Interface pour les gestionnaires d'entités
 */
interface EntityHandlerInterface
{
    /**
     * Initialise le gestionnaire d'entités
     * 
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités Doctrine
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void;
    
    /**
     * Récupère ou crée une entité à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return object|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?object;
} 