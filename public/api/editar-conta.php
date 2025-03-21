<?php
require_once __DIR__ . '/../../bootstrap.php';

use App\Services\ContasReceberService;

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('ID não fornecido');
    }

    $dados = json_decode(file_get_contents('php://input'), true);
    
    if (!$dados) {
        throw new Exception('Dados inválidos');
    }

    $contasService = new ContasReceberService();
    $resultado = $contasService->atualizarConta($_GET['id'], $dados);
    
    echo json_encode([
        'success' => true,
        'data' => $resultado
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
