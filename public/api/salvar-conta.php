<?php
require_once __DIR__ . '/../../bootstrap.php';

use App\Services\ContasReceberService;

header('Content-Type: application/json');

try {
    // Obter dados do request
    $dados = json_decode(file_get_contents('php://input'), true);
    
    if (!$dados) {
        throw new Exception('Dados inválidos');
    }

    // Instanciar o serviço
    $contasService = new ContasReceberService();
    
    // Salvar a conta
    $resultado = $contasService->salvarConta($dados);
    
    // Retornar resposta
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
