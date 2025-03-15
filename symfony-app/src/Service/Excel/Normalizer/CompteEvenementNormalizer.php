<?php

namespace App\Service\Excel\Normalizer;

use App\Entity\CompteEvenement;
use App\Entity\FicheTechniqueVoiture;
use App\Service\Excel\AbstractEntityNormalizer;

/**
 * Normalisation de l'entité CompteEvenement
 */
class CompteEvenementNormalizer extends AbstractEntityNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function normaliserValeur(string $valeur): string
    {
        // Pour les comptes événement, on normalise simplement en supprimant les espaces et en mettant en majuscules
        return strtoupper(trim($valeur));
    }

    /**
     * {@inheritdoc}
     */
    public function getProprieteNom(): string
    {
        return 'nomCompteEvenement';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return CompteEvenement::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedEntities(): array
    {
        return [
            FicheTechniqueVoiture::class => [
                'propriete' => 'compteEvenement',
                'getter' => 'getCompteEvenement',
                'setter' => 'setCompteEvenement'
            ]
        ];
    }
} 