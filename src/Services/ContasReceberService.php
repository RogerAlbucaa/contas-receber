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

    public function getConta($id) {
        return $this->supabase->request("/rest/v1/vw_contas_receber?id=eq.{$id}&select=*")->first();
    }

    public function salvarConta($dados) {
        // Validar dados obrigatórios
        $camposObrigatorios = ['descricao', 'entidade_id', 'plano_conta_id', 
                              'forma_pagamento_id', 'data_vencimento', 'valor_total'];
        
        foreach ($camposObrigatorios as $campo) {
            if (empty($dados[$campo])) {
                throw new \Exception("Campo {$campo} é obrigatório");
            }
        }

        // Gerar código automaticamente
        $dados['codigo'] = $this->gerarCodigo();
        
        // Garantir valores padrão
        $dados['verificado'] = false;
        $dados['data_emissao'] = $dados['data_emissao'] ?? date('Y-m-d');
        
        // Converter valores
        $dados['valor_total'] = floatval($dados['valor_total']);
        
        return $this->supabase->request('/rest/v1/contas_receber', 'POST', $dados);
    }

    private function gerarCodigo() {
        try {
            $ultimaConta = $this->supabase->request('/rest/v1/contas_receber?select=codigo&order=codigo.desc&limit=1');
            
            if (empty($ultimaConta)) {
                return '1001';
            }
            
            $ultimoCodigo = intval($ultimaConta[0]['codigo']);
            return sprintf('%04d', $ultimoCodigo + 1);
        } catch (\Exception $e) {
            // Fallback: gerar código baseado na data + número aleatório
            return date('ymd') . sprintf('%04d', rand(1, 9999));
        }
    }

    public function atualizarConta($id, $dados) {
        return $this->supabase->request("/rest/v1/contas_receber?id=eq.{$id}", 'PATCH', $dados);
    }

    public function getEntidades() {
        return $this->supabase->request('/rest/v1/entidades?select=*');
    }

    public function getPlanoContas() {
        return $this->supabase->request('/rest/v1/plano_contas?select=*');
    }

    public function getFormasPagamento() {
        return $this->supabase->request('/rest/v1/formas_pagamento?select=*');
    }
}
