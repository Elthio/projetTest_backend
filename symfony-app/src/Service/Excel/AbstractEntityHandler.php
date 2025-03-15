<?php

namespace App\Service\Excel;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Classe abstraite pour les gestionnaires d'entités
 */
abstract class AbstractEntityHandler implements EntityHandlerInterface
{
    protected EntityManagerInterface $entityManager;
    
    /**
     * Initialise le gestionnaire d'entités
     * 
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités Doctrine
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Récupère ou crée une entité à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return object|null L'entité créée ou récupérée
     */
    abstract public function getOrCreateEntity(array $data): ?object;
    
    /**
     * Parse une date à partir d'une chaîne
     */
    protected function parseDate(?string $dateString): ?\DateTime
    {
        if (empty($dateString)) {
            return new \DateTime();
        }
        
        try {
            // Essayer différents formats de date
            $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'Y/m/d'];
            
            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $dateString);
                if ($date !== false) {
                    return $date;
                }
            }
            
            // Si aucun format ne correspond, essayer de parser la date
            return new \DateTime($dateString);
        } catch (\Exception $e) {
            return new \DateTime();
        }
    }
} 