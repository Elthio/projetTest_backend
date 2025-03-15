<?php

namespace App\Service\Excel\Normalizer;

use App\Entity\OrigineEvenement;
use App\Entity\FicheTechniqueVoiture;
use App\Service\Excel\AbstractEntityNormalizer;

/**
 * Normalisation de l'entité OrigineEvenement
 */
class OrigineEvenementNormalizer extends AbstractEntityNormalizer
{
    // Liste des origines d'événement normalisées
    private const ORIGINES_EVENEMENT_NORMALISEES = [
        'VISITE SHOWROOM' => ['VISITE SHOWROOM', 'SHOWROOM', 'VISITE', 'MAGASIN'],
        'APPEL TÉLÉPHONIQUE' => ['APPEL TÉLÉPHONIQUE', 'APPEL', 'TELEPHONE', 'TEL', 'CALL'],
        'SITE WEB' => ['SITE WEB', 'WEB', 'INTERNET', 'SITE', 'ONLINE'],
        'SALON AUTOMOBILE' => ['SALON AUTOMOBILE', 'SALON', 'EXPO', 'EXPOSITION'],
        'RECOMMANDATION' => ['RECOMMANDATION', 'RECOMMANDÉ', 'RECOMMANDE', 'PARRAINAGE']
    ];

    /**
     * {@inheritdoc}
     */
    public function normaliserValeur(string $valeur): string
    {
        $valeurUppercase = strtoupper(trim($valeur));
        
        foreach (self::ORIGINES_EVENEMENT_NORMALISEES as $typeNormalise => $variantes) {
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
        return 'nomOrigineEvenement';
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityClass(): string
    {
        return OrigineEvenement::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelatedEntities(): array
    {
        return [
            FicheTechniqueVoiture::class => [
                'propriete' => 'origineEvenement',
                'getter' => 'getOrigineEvenement',
                'setter' => 'setOrigineEvenement'
            ]
        ];
    }
} 