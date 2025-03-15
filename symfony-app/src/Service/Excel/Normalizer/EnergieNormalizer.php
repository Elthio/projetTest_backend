<?php

namespace App\Service\Excel\Normalizer;

use App\Entity\Energie;
use App\Entity\Voiture;
use App\Entity\FicheTechniqueVoiture;
use App\Service\Excel\AbstractEntityNormalizer;

/**
 * Normalisation de l'entité Energie
 */
class EnergieNormalizer extends AbstractEntityNormalizer
{
    // Liste des types d'énergie normalisés
    private const ENERGIES_NORMALISEES = [
        'ESSENCE' => ['ESSENCE', 'ESS', 'SP95', 'SP98', 'SUPER'],
        'DIESEL' => ['DIESEL', 'GAZOLE', 'GO', 'GASOIL'],
        'HYBRIDE' => ['HYBRIDE', 'HYBRID', 'HYB'],
        'PLUG-IN' => ['PLUG-IN', 'PHEV', 'HYBRIDE RECHARGEABLE', 'PLUG-IN HYBRID']
    ];

    /**
     * {@inheritdoc}
     */
    public function normaliserValeur(string $valeur): string
    {
        $valeurUppercase = strtoupper(trim($valeur));
        
        foreach (self::ENERGIES_NORMALISEES as $typeNormalise => $variantes) {
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
        return 'nomEnergie';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return Energie::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedEntities(): array
    {
        return [
            Voiture::class => [
                'propriete' => 'energie',
                'getter' => 'getEnergie',
                'setter' => 'setEnergie'
            ],
            FicheTechniqueVoiture::class => [
                'propriete' => 'energie',
                'getter' => 'getEnergie',
                'setter' => 'setEnergie'
            ]
        ];
    }
} 