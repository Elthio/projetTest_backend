<?php

namespace App\Service\Excel\Normalizer;

use App\Entity\LibelleCivilite;
use App\Entity\Proprio;
use App\Service\Excel\AbstractEntityNormalizer;

/**
 * Normalisation de l'entité LibelleCivilite
 */
class LibelleCiviliteNormalizer extends AbstractEntityNormalizer
{
    // Liste des civilités normalisées
    private const CIVILITES_NORMALISEES = [
        'M.' => ['M.', 'M', 'MR', 'MONSIEUR', 'MR.'],
        'Mme' => ['MME', 'MME.', 'MADAME', 'MS', 'MS.', 'MISS', 'MRS', 'MRS.'],
        'Mlle' => ['MLLE', 'MLLE.', 'MADEMOISELLE'],
        'Dr' => ['DR', 'DR.', 'DOCTEUR', 'DOCTOR'],
        'Pr' => ['PR', 'PR.', 'PROFESSEUR', 'PROFESSOR']
    ];

    /**
     * {@inheritdoc}
     */
    public function normaliserValeur(string $valeur): string
    {
        $valeurUppercase = strtoupper(trim($valeur));
        
        foreach (self::CIVILITES_NORMALISEES as $typeNormalise => $variantes) {
            foreach ($variantes as $variante) {
                if ($valeurUppercase === $variante) {
                    return $typeNormalise;
                }
            }
        }
        
        // Si aucune correspondance n'est trouvée, retourner la valeur originale
        return $valeur;
    }

    /**
     * {@inheritdoc}
     */
    public function getProprieteNom(): string
    {
        return 'nomLibelleCivilite';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return LibelleCivilite::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedEntities(): array
    {
        return [
            Proprio::class => [
                'propriete' => 'libelleCivilite',
                'getter' => 'getLibelleCivilite',
                'setter' => 'setLibelleCivilite'
            ]
        ];
    }
} 