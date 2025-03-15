<?php

namespace App\Controller;

use App\Service\ExcelImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    private ExcelImportService $excelImportService;

    public function __construct(ExcelImportService $excelImportService)
    {
        $this->excelImportService = $excelImportService;
    }

    /**
     * @Route("/admin/import", name="app_import")
     */
    public function index(): Response
    {
        return $this->render('import/index.html.twig');
    }

    /**
     * @Route("/admin/import/excel", name="app_import_excel", methods={"POST"})
     */
    public function importExcel(Request $request): Response
    {
        $file = $request->files->get('excel_file');
        
        if (!$file) {
            $this->addFlash('error', 'Aucun fichier n\'a été téléchargé.');
            return $this->redirectToRoute('app_import');
        }
        
        // Vérifier l'extension du fichier
        $extension = $file->getClientOriginalExtension();
        if (!in_array($extension, ['xlsx', 'xls'])) {
            $this->addFlash('error', 'Le fichier doit être au format Excel (.xlsx ou .xls).');
            return $this->redirectToRoute('app_import');
        }
        
        try {
            // Importer le fichier
            $stats = $this->excelImportService->importExcelFile($file);
            
            // Afficher les statistiques
            $this->addFlash('success', sprintf(
                'Importation terminée. %d lignes sur %d ont été importées.',
                $stats['imported_rows'],
                $stats['total_rows']
            ));
            
            // Afficher les erreurs s'il y en a
            if (!empty($stats['errors'])) {
                foreach ($stats['errors'] as $error) {
                    $this->addFlash('warning', $error);
                }
            }
            
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de l\'importation : ' . $e->getMessage());
        }
        
        return $this->redirectToRoute('app_import');
    }
} 