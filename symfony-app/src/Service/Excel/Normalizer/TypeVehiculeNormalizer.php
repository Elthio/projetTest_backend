<?php

namespace App\Service\Excel\Normalizer;

use App\Entity\TypeVehicule;
use App\Entity\FicheVente;
use App\Entity\FicheTechniqueVoiture;
use App\Service\Excel\AbstractEntityNormalizer;

/**
 * Normalisation de l'entité TypeVehicule
 */
class TypeVehiculeNormalizer extends AbstractEntityNormalizer
{
    // Liste des types de véhicule normalisés
    private const TYPES_VEHICULE_NORMALISES = [
        'NEUF' => ['NEUF', 'VN', 'NEW', 'N'],
        'OCCASION' => ['OCCASION', 'VO', 'USED', 'O', 'OCC'],
        'DÉMONSTRATION' => ['DÉMONSTRATION', 'DEMO', 'DEMONSTRATION', 'DEM'],
        'DIRECTION' => ['DIRECTION', 'DIR']
    ];

    /**
     * {@inheritdoc}
     */
    public function normaliserValeur(string $valeur): string
    {
        $valeurUppercase = strtoupper(trim($valeur));
        
        foreach (self::TYPES_VEHICULE_NORMALISES as $typeNormalise => $variantes) {
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
        return 'nomTypeVehicule';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return TypeVehicule::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedEntities(): array
    {
        return [
            FicheVente::class => [
                'propriete' => 'typeVehicule',
                'getter' => 'getTypeVehicule',
                'setter' => 'setTypeVehicule'
            ],
            FicheTechniqueVoiture::class => [
                'propriete' => 'typeVehicule',
                'getter' => 'getTypeVehicule',
                'setter' => 'setTypeVehicule'
            ]
        ];
    }
} 