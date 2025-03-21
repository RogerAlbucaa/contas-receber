<?php
require_once __DIR__ . '/../../bootstrap.php';

use App\Services\ContasReceberService;

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('ID não fornecido');
    }

    $contasService = new ContasReceberService();
    $conta = $contasService->getConta($_GET['id']);
    
    if (!$conta) {
        throw new Exception('Conta não encontrada');
    }

    echo json_encode([
        'success' => true,
        'data' => $conta
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
