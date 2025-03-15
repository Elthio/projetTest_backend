<?php

namespace App\Command;

use App\Service\ExcelImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportExcelCommand extends Command
{
    protected static $defaultName = 'app:import:excel';
    protected static $defaultDescription = 'Importe les données depuis un fichier Excel';

    private ExcelImportService $excelImportService;

    public function __construct(ExcelImportService $excelImportService)
    {
        parent::__construct();
        $this->excelImportService = $excelImportService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Chemin vers le fichier Excel à importer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('file');

        if (!file_exists($filePath)) {
            $io->error(sprintf('Le fichier "%s" n\'existe pas.', $filePath));
            return Command::FAILURE;
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!in_array($extension, ['xlsx', 'xls'])) {
            $io->error('Le fichier doit être au format Excel (.xlsx ou .xls).');
            return Command::FAILURE;
        }

        $io->title('Importation des données Excel');
        $io->text('Importation en cours...');

        try {
            // Créer un UploadedFile à partir du fichier
            $file = new UploadedFile(
                $filePath,
                basename($filePath),
                null,
                null,
                true
            );

            // Importer le fichier
            $stats = $this->excelImportService->importExcelFile($file);

            // Afficher les statistiques
            $io->success(sprintf(
                'Importation terminée. %d lignes sur %d ont été importées.',
                $stats['imported_rows'],
                $stats['total_rows']
            ));

            // Afficher les erreurs s'il y en a
            if (!empty($stats['errors'])) {
                $io->section('Erreurs rencontrées');
                foreach ($stats['errors'] as $error) {
                    $io->text('- ' . $error);
                }
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Une erreur est survenue lors de l\'importation : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
} 