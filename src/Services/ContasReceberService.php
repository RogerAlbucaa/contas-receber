<?php
namespace App\Services;

require_once __DIR__ . '/../../supabase/config.php';

class ContasReceberService {
    private $supabase;

    public function __construct() {
        $this->supabase = \SupabaseConfig::getInstance();
    }

    public function listarContas() {
        return $this->supabase->request('/rest/v1/vw_contas_receber?select=*');
    }

    public function getTotais() {
        $contas = $this->listarContas();
        $totais = [
            'vencidos' => 0,
            'vencendo' => 0,
            'a_vencer' => 0,
            'recebidos' => 0,
            'total' => 0
        ];

        foreach ($contas as $conta) {
            $valor = floatval($conta['valor_total']);
            $totais['total'] += $valor;

            switch ($conta['situacao']) {
                case 'vencido':
                    $totais['vencidos'] += $valor;
                    break;
                case 'vencendo':
                    $totais['vencendo'] += $valor;
                    break;
                case 'pendente':
                    $totais['a_vencer'] += $valor;
                    break;
                case 'recebido':
                    $totais['recebidos'] += $valor;
                    break;
            }
        }

        return $totais;
    }
}
