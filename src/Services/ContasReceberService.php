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
        return $this->supabase->request('/rest/v1/contas_receber', 'POST', $dados);
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
