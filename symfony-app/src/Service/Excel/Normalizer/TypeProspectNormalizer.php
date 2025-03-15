<?php

namespace App\Service\Excel\Normalizer;

use App\Entity\TypeProspect;
use App\Entity\Proprio;
use App\Service\Excel\AbstractEntityNormalizer;

/**
 * Normalisation de l'entité TypeProspect
 */
class TypeProspectNormalizer extends AbstractEntityNormalizer
{
    // Liste des types de prospect normalisés
    private const TYPES_PROSPECT_NORMALISES = [
        'PARTICULIER' => ['PARTICULIER', 'PART', 'PRIVÉ', 'PRIVE', 'INDIVIDUAL'],
        'PROFESSIONNEL' => ['PROFESSIONNEL', 'PRO', 'ENTREPRISE', 'SOCIÉTÉ', 'SOCIETE', 'BUSINESS'],
        'ADMINISTRATION' => ['ADMINISTRATION', 'ADMIN', 'PUBLIC', 'COLLECTIVITÉ', 'COLLECTIVITE'],
        'ASSOCIATION' => ['ASSOCIATION', 'ASSO', 'ORGANISME', 'NON-PROFIT']
    ];

    /**
     * {@inheritdoc}
     */
    public function normaliserValeur(string $valeur): string
    {
        $valeurUppercase = strtoupper(trim($valeur));
        
        foreach (self::TYPES_PROSPECT_NORMALISES as $typeNormalise => $variantes) {
            foreach ($variantes as $variante) {
                if (strpos($valeurUppercase, $variante) !== false) {
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
        return 'nomTypeProspect';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return TypeProspect::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedEntities(): array
    {
        return [
            Proprio::class => [
                'propriete' => 'typeProspect',
                'getter' => 'getTypeProspect',
                'setter' => 'setTypeProspect'
            ]
        ];
    }
} 