<?php

namespace App\Service\Excel\Handler;

use App\Entity\Energie;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité Energie
 */
class EnergieHandler extends AbstractEntityHandler
{
    // Liste des types d'énergie normalisés
    private const ENERGIES_NORMALISEES = [
        'ESSENCE' => ['ESSENCE', 'ESS', 'SP95', 'SP98', 'SUPER'],
        'DIESEL' => ['DIESEL', 'GAZOLE', 'GO', 'GASOIL'],
        'HYBRIDE' => ['HYBRIDE', 'HYBRID', 'HYB'],
        'PLUG-IN' => ['PLUG-IN', 'PHEV', 'HYBRIDE RECHARGEABLE', 'PLUG-IN HYBRID']
    ];

    /**
     * Normalise le nom de l'énergie pour éviter les redondances
     */
    private function normaliserNomEnergie(string $nomEnergie): string
    {
        $nomUppercase = strtoupper(trim($nomEnergie));
        
        foreach (self::ENERGIES_NORMALISEES as $typeNormalise => $variantes) {
            foreach ($variantes as $variante) {
                if (strpos($nomUppercase, $variante) !== false) {
                    return $typeNormalise;
                }
            }
        }
        
        // Si aucune correspondance n'est trouvée, retourner le nom original
        return $nomEnergie;
    }

    /**
     * Récupère ou crée une Energie à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return Energie|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?Energie
    {
        $nomEnergie = $data['Libellé énergie (Energ)'] ?? null;
        
        if (empty($nomEnergie)) {
            return null;
        }
        
        // Normaliser le nom de l'énergie pour éviter les redondances
        $nomEnergieNormalise = $this->normaliserNomEnergie($nomEnergie);
        
        $energie = $this->entityManager->getRepository(Energie::class)
            ->findOneBy(['nomEnergie' => $nomEnergieNormalise]);
        
        if (!$energie) {
            $energie = new Energie();
            $energie->setNomEnergie($nomEnergieNormalise);
            $this->entityManager->persist($energie);
        }
        
        return $energie;
    }
} 