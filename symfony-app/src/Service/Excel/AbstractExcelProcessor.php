<?php

namespace App\Service\Excel;

use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use \DateTime;

/**
 * Classe abstraite pour le traitement des fichiers Excel
 */
abstract class AbstractExcelProcessor
{
    protected EntityManagerInterface $entityManager;
    protected array $stats = [
        'total_rows' => 0,
        'imported_rows' => 0,
        'errors' => [],
    ];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Traite un fichier Excel
     * 
     * @param UploadedFile|string $file Le fichier Excel à traiter
     * @return array Statistiques de traitement
     */
    public function processFile($file): array
    {
        $this->stats = [
            'total_rows' => 0,
            'imported_rows' => 0,
            'errors' => [],
        ];

        try {
            // Charger le fichier Excel
            if ($file instanceof UploadedFile) {
                $spreadsheet = IOFactory::load($file->getPathname());
            } else {
                $spreadsheet = IOFactory::load($file);
            }
            
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Récupérer les données (sans la première ligne d'en-tête)
            $data = $worksheet->toArray();
            $headers = array_shift($data);
            
            $this->stats['total_rows'] = count($data);
            
            // Traiter chaque ligne
            foreach ($data as $rowIndex => $row) {
                try {
                    $this->processRow($headers, $row);
                    $this->stats['imported_rows']++;
                } catch (\Exception $e) {
                    $this->stats['errors'][] = "Ligne " . ($rowIndex + 2) . ": " . $e->getMessage();
                }
            }
            
            // Enregistrer les modifications en base de données
            $this->entityManager->flush();
            
        } catch (\Exception $e) {
            $this->stats['errors'][] = "Erreur globale: " . $e->getMessage();
        }
        
        return $this->stats;
    }

    /**
     * Traite une ligne du fichier Excel
     * 
     * @param array $headers Les en-têtes du fichier
     * @param array $row Les données de la ligne
     */
    abstract protected function processRow(array $headers, array $row): void;

    /**
     * Crée un tableau associatif avec les en-têtes
     * 
     * @param array $headers Les en-têtes du fichier
     * @param array $row Les données de la ligne
     * @return array Tableau associatif
     */
    protected function createRowData(array $headers, array $row): array
    {
        $rowData = [];
        foreach ($headers as $index => $header) {
            if (isset($row[$index])) {
                $rowData[$header] = $row[$index];
            } else {
                $rowData[$header] = null;
            }
        }
        return $rowData;
    }

    /**
     * Parse une date à partir d'une chaîne
     */
    protected function parseDate(?string $dateString): ?DateTime
    {
        if (empty($dateString)) {
            return new DateTime();
        }
        
        try {
            // Essayer différents formats de date
            $formats = ['d/m/Y', 'Y-m-d', 'd-m-Y', 'Y/m/d'];
            
            foreach ($formats as $format) {
                $date = DateTime::createFromFormat($format, $dateString);
                if ($date !== false) {
                    return $date;
                }
            }
            
            // Si aucun format ne correspond, essayer de parser la date
            return new DateTime($dateString);
        } catch (\Exception $e) {
            return new DateTime();
        }
    }
} 