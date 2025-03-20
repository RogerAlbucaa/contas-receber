<?php
namespace App\Utils;

class Formatter {
    public static function formatarMoeda($valor) {
        return number_format($valor, 2, ',', '.');
    }

    public static function formatarData($data) {
        if (!$data) return '';
        return date('d/m/Y', strtotime($data));
    }

    public static function getSituacaoBadgeClass($situacao) {
        switch ($situacao) {
            case 'recebido':
                return 'badge-recebido';
            case 'cancelado':
                return 'bg-danger';
            case 'vencido':
                return 'status-vencido';
            case 'vencendo':
                return 'status-vencendo';
            default:
                return 'bg-secondary';
        }
    }
}
