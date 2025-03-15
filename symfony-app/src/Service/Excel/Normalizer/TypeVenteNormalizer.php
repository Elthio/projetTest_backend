<?php

namespace App\Service\Excel\Normalizer;

use App\Entity\TypeVentes;
use App\Entity\FicheVente;
use App\Entity\FicheTechniqueVoiture;
use App\Service\Excel\AbstractEntityNormalizer;

/**
 * Normalisation de l'entité TypeVentes
 */
class TypeVenteNormalizer extends AbstractEntityNormalizer
{
    // Liste des types de vente normalisés
    private const TYPES_VENTE_NORMALISES = [
        'VENTE DIRECTE' => ['VENTE DIRECTE', 'DIRECT', 'VD'],
        'LEASING' => ['LEASING', 'LOA', 'LLD'],
        'CRÉDIT' => ['CRÉDIT', 'CREDIT', 'FINANCEMENT'],
        'REPRISE' => ['REPRISE', 'RACHAT', 'TRADE-IN']
    ];

    /**
     * {@inheritdoc}
     */
    public function normaliserValeur(string $valeur): string
    {
        $valeurUppercase = strtoupper(trim($valeur));
        
        foreach (self::TYPES_VENTE_NORMALISES as $typeNormalise => $variantes) {
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
        return 'nomTypeVente';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return TypeVentes::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedEntities(): array
    {
        return [
            FicheVente::class => [
                'propriete' => 'typeVente',
                'getter' => 'getTypeVente',
                'setter' => 'setTypeVente'
            ],
            FicheTechniqueVoiture::class => [
                'propriete' => 'typeVente',
                'getter' => 'getTypeVente',
                'setter' => 'setTypeVente'
            ]
        ];
    }
} 