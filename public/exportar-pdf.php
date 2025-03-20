<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use App\Services\ContasReceberService;
use App\Services\ExportService;
use App\Utils\Formatter;

try {
    $contasService = new ContasReceberService();
    $exportService = new ExportService();
    
    $contas = $contasService->listarContas();
    $pdf = $exportService->exportarPDF($contas);
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="contas-receber.pdf"');
    echo $pdf;
} catch (Exception $e) {
    echo "Erro ao gerar PDF: " . $e->getMessage();
}
