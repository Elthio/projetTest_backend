<?php

namespace App\Command;

use App\Service\Excel\Normalizer\CompteAffaireNormalizer;
use App\Service\Excel\Normalizer\CompteEvenementNormalizer;
use App\Service\Excel\Normalizer\EnergieNormalizer;
use App\Service\Excel\Normalizer\LibelleCiviliteNormalizer;
use App\Service\Excel\Normalizer\OrigineEvenementNormalizer;
use App\Service\Excel\Normalizer\TypeProspectNormalizer;
use App\Service\Excel\Normalizer\TypeVehiculeNormalizer;
use App\Service\Excel\Normalizer\TypeVenteNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NettoyerTablesReferenceCommand extends Command
{
    protected static $defaultName = 'app:nettoyer:tables-reference';
    protected static $defaultDescription = 'Nettoie les données des tables de référence pour éliminer les redondances';

    private EntityManagerInterface $entityManager;
    private array $normalizers = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        
        // Initialiser les normaliseurs
        $this->normalizers = [
            'energie' => new EnergieNormalizer($entityManager),
            'type_vehicule' => new TypeVehiculeNormalizer($entityManager),
            'type_vente' => new TypeVenteNormalizer($entityManager),
            'origine_evenement' => new OrigineEvenementNormalizer($entityManager),
            'type_prospect' => new TypeProspectNormalizer($entityManager),
            'libelle_civilite' => new LibelleCiviliteNormalizer($entityManager),
            'compte_affaire' => new CompteAffaireNormalizer($entityManager),
            'compte_evenement' => new CompteEvenementNormalizer($entityManager)
        ];
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('table', 't', InputOption::VALUE_OPTIONAL, 'Table spécifique à nettoyer (energie, type_vehicule, type_vente, origine_evenement, type_prospect, libelle_civilite, compte_affaire, compte_evenement)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Nettoyage des données des tables de référence');

        $tableOption = $input->getOption('table');
        $statsGlobales = [
            'total' => 0,
            'normalises' => 0,
            'supprimes' => 0,
            'relations_mises_a_jour' => 0
        ];

        if ($tableOption && !isset($this->normalizers[$tableOption])) {
            $io->error(sprintf('La table "%s" n\'existe pas ou n\'est pas prise en charge.', $tableOption));
            $io->info('Tables disponibles : ' . implode(', ', array_keys($this->normalizers)));
            return Command::FAILURE;
        }

        // Si une table spécifique est demandée, ne nettoyer que celle-là
        if ($tableOption) {
            $normalizer = $this->normalizers[$tableOption];
            $normalizer->setOutput($io);
            
            $io->section(sprintf('Nettoyage de la table %s', $tableOption));
            $stats = $normalizer->nettoyer();
            
            $this->afficherStats($io, $stats);
            $statsGlobales = $this->ajouterStats($statsGlobales, $stats);
        } else {
            // Sinon, nettoyer toutes les tables
            foreach ($this->normalizers as $nom => $normalizer) {
                $normalizer->setOutput($io);
                
                $io->section(sprintf('Nettoyage de la table %s', $nom));
                $stats = $normalizer->nettoyer();
                
                $this->afficherStats($io, $stats);
                $statsGlobales = $this->ajouterStats($statsGlobales, $stats);
            }
        }

        $io->newLine();
        $io->section('Statistiques globales');
        $this->afficherStats($io, $statsGlobales);

        $io->success('Nettoyage des données des tables de référence terminé avec succès !');

        return Command::SUCCESS;
    }

    /**
     * Affiche les statistiques de nettoyage
     */
    private function afficherStats(SymfonyStyle $io, array $stats): void
    {
        $io->table(
            ['Métrique', 'Valeur'],
            [
                ['Entités trouvées', $stats['total']],
                ['Entités normalisées', $stats['normalises']],
                ['Entités supprimées', $stats['supprimes']],
                ['Relations mises à jour', $stats['relations_mises_a_jour']]
            ]
        );
    }

    /**
     * Ajoute des statistiques aux statistiques globales
     */
    private function ajouterStats(array $statsGlobales, array $stats): array
    {
        return [
            'total' => $statsGlobales['total'] + $stats['total'],
            'normalises' => $statsGlobales['normalises'] + $stats['normalises'],
            'supprimes' => $statsGlobales['supprimes'] + $stats['supprimes'],
            'relations_mises_a_jour' => $statsGlobales['relations_mises_a_jour'] + $stats['relations_mises_a_jour']
        ];
    }
} 