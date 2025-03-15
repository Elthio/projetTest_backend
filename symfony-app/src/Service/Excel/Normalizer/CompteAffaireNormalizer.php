<?php

namespace App\Service\Excel\Normalizer;

use App\Entity\CompteAffaire;
use App\Entity\FicheTechniqueVoiture;
use App\Service\Excel\AbstractEntityNormalizer;

/**
 * Normalisation de l'entité CompteAffaire
 */
class CompteAffaireNormalizer extends AbstractEntityNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function normaliserValeur(string $valeur): string
    {
        // Pour les comptes affaire, on normalise simplement en supprimant les espaces et en mettant en majuscules
        return strtoupper(trim($valeur));
    }

    /**
     * {@inheritdoc}
     */
    public function getProprieteNom(): string
    {
        return 'nomCompteAffaire';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return CompteAffaire::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedEntities(): array
    {
        return [
            FicheTechniqueVoiture::class => [
                'propriete' => 'compteAffaire',
                'getter' => 'getCompteAffaire',
                'setter' => 'setCompteAffaire'
            ]
        ];
    }
} 