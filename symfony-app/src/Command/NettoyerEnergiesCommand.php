<?php

namespace App\Command;

use App\Entity\Energie;
use App\Entity\Voiture;
use App\Entity\FicheTechniqueVoiture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NettoyerEnergiesCommand extends Command
{
    protected static $defaultName = 'app:nettoyer:energies';
    protected static $defaultDescription = 'Nettoie les données de la table Energie pour éliminer les redondances';

    private EntityManagerInterface $entityManager;

    // Liste des types d'énergie normalisés
    private const ENERGIES_NORMALISEES = [
        'ESSENCE' => ['ESSENCE', 'ESS', 'SP95', 'SP98', 'SUPER'],
        'DIESEL' => ['DIESEL', 'GAZOLE', 'GO', 'GASOIL'],
        'HYBRIDE' => ['HYBRIDE', 'HYBRID', 'HYB'],
        'PLUG-IN' => ['PLUG-IN', 'PHEV', 'HYBRIDE RECHARGEABLE', 'PLUG-IN HYBRID']
    ];

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Nettoyage des données de la table Energie');

        // 1. Récupérer toutes les énergies
        $energies = $this->entityManager->getRepository(Energie::class)->findAll();
        $io->info(sprintf('Nombre total d\'énergies trouvées : %d', count($energies)));

        // 2. Créer un tableau pour stocker les énergies normalisées
        $energiesNormalisees = [];
        $mapEnergies = [];

        // 3. Parcourir toutes les énergies et les normaliser
        foreach ($energies as $energie) {
            $nomOriginal = $energie->getNomEnergie();
            $nomNormalise = $this->normaliserNomEnergie($nomOriginal);
            
            if (!isset($energiesNormalisees[$nomNormalise])) {
                $energiesNormalisees[$nomNormalise] = $energie;
                $io->text(sprintf('Énergie conservée : %s => %s', $nomOriginal, $nomNormalise));
                
                // Mettre à jour le nom si nécessaire
                if ($nomOriginal !== $nomNormalise) {
                    $energie->setNomEnergie($nomNormalise);
                    $this->entityManager->persist($energie);
                    $io->text(sprintf('  - Nom mis à jour : %s => %s', $nomOriginal, $nomNormalise));
                }
            }
            
            // Stocker la correspondance entre l'énergie originale et l'énergie normalisée
            $mapEnergies[$energie->getIdenergie()] = $energiesNormalisees[$nomNormalise];
        }

        $io->info(sprintf('Nombre d\'énergies après normalisation : %d', count($energiesNormalisees)));

        // 4. Mettre à jour les relations dans la table Voiture
        $voitures = $this->entityManager->getRepository(Voiture::class)->findAll();
        $compteurVoitures = 0;
        
        foreach ($voitures as $voiture) {
            $energieActuelle = $voiture->getEnergie();
            
            if ($energieActuelle && isset($mapEnergies[$energieActuelle->getIdenergie()])) {
                $energieNormalisee = $mapEnergies[$energieActuelle->getIdenergie()];
                
                if ($energieActuelle->getIdenergie() !== $energieNormalisee->getIdenergie()) {
                    $voiture->setEnergie($energieNormalisee);
                    $this->entityManager->persist($voiture);
                    $compteurVoitures++;
                }
            }
        }
        
        $io->info(sprintf('Nombre de voitures mises à jour : %d', $compteurVoitures));

        // 5. Mettre à jour les relations dans la table FicheTechniqueVoiture
        $fichesTechniques = $this->entityManager->getRepository(FicheTechniqueVoiture::class)->findAll();
        $compteurFiches = 0;
        
        foreach ($fichesTechniques as $ficheTechnique) {
            $energieActuelle = $ficheTechnique->getEnergie();
            
            if ($energieActuelle && isset($mapEnergies[$energieActuelle->getIdenergie()])) {
                $energieNormalisee = $mapEnergies[$energieActuelle->getIdenergie()];
                
                if ($energieActuelle->getIdenergie() !== $energieNormalisee->getIdenergie()) {
                    $ficheTechnique->setEnergie($energieNormalisee);
                    $this->entityManager->persist($ficheTechnique);
                    $compteurFiches++;
                }
            }
        }
        
        $io->info(sprintf('Nombre de fiches techniques mises à jour : %d', $compteurFiches));

        // 6. Supprimer les énergies redondantes
        $compteurSuppression = 0;
        
        foreach ($energies as $energie) {
            $nomNormalise = $this->normaliserNomEnergie($energie->getNomEnergie());
            $energieNormalisee = $energiesNormalisees[$nomNormalise];
            
            if ($energie->getIdenergie() !== $energieNormalisee->getIdenergie()) {
                // Vérifier qu'il n'y a plus de relations avec cette énergie
                $voituresLiees = $this->entityManager->getRepository(Voiture::class)
                    ->findBy(['energie' => $energie]);
                    
                $fichesLiees = $this->entityManager->getRepository(FicheTechniqueVoiture::class)
                    ->findBy(['energie' => $energie]);
                
                if (count($voituresLiees) === 0 && count($fichesLiees) === 0) {
                    $this->entityManager->remove($energie);
                    $compteurSuppression++;
                    $io->text(sprintf('Suppression de l\'énergie redondante : %s (ID: %d)', $energie->getNomEnergie(), $energie->getIdenergie()));
                } else {
                    $io->warning(sprintf('Impossible de supprimer l\'énergie %s (ID: %d) car elle est encore utilisée', $energie->getNomEnergie(), $energie->getIdenergie()));
                }
            }
        }
        
        $io->info(sprintf('Nombre d\'énergies supprimées : %d', $compteurSuppression));

        // 7. Enregistrer les modifications
        $this->entityManager->flush();

        $io->success('Nettoyage des données de la table Energie terminé avec succès !');

        return Command::SUCCESS;
    }
} 