<?php
namespace App\Services;

use Dompdf\Dompdf;
use App\Utils\Formatter;

class ExportService {
    public function exportarPDF($contas) {
        $dompdf = new Dompdf([
            'enable_remote' => true,
            'defaultFont' => 'Arial'
        ]);
        
        $html = '
        <html>
        <head>
            <style>
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f5f5f5; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
            </style>
        </head>
        <body>
            <h2 style="text-align: center">Relatório de Contas a Receber</h2>
            <p style="text-align: center">Data de geração: ' . date('d/m/Y H:i:s') . '</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Entidade</th>
                        <th>Plano de Contas</th>
                        <th>Pagamento</th>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Situação</th>
                        <th>Lote</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($contas as $conta) {
            $html .= '<tr>
                <td>' . $conta['codigo'] . '</td>
                <td>' . $conta['descricao'] . '</td>
                <td>' . $conta['entidade'] . '</td>
                <td>' . $conta['plano_conta'] . '</td>
                <td>' . $conta['forma_pagamento'] . '</td>
                <td>' . Formatter::formatarData($conta['data_vencimento']) . '</td>
                <td class="text-right">' . Formatter::formatarMoeda($conta['valor_total']) . '</td>
                <td class="text-center">' . ucfirst($conta['situacao']) . '</td>
                <td>' . $conta['lote'] . '</td>
            </tr>';
        }
        
        $html .= '</tbody></table></body></html>';
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        return $dompdf->output();
    }
}
