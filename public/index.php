<?php 
require_once __DIR__ . '/../bootstrap.php';

// Update namespaces
use App\Services\ContasReceberService;
use App\Utils\Formatter;

try {
    $contasService = new ContasReceberService();
    $totais = $contasService->getTotais();
    $contas = $contasService->listarContas();
} catch (Exception $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contas a Receber - Kendo UI</title>
    <link href="https://kendo.cdn.telerik.com/2018.3.911/styles/kendo.common.min.css" rel="stylesheet" />
    <link href="https://kendo.cdn.telerik.com/2018.3.911/styles/kendo.default.min.css" rel="stylesheet" />
    <link href="https://kendo.cdn.telerik.com/2018.3.911/styles/kendo.default.mobile.min.css" rel="stylesheet" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="css/styles.css" rel="stylesheet" />
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kendo.cdn.telerik.com/2018.3.911/js/kendo.all.min.js"></script>
</head>
<body>
    <div class="main-container">
        <div class="card">
            <!-- Window header with dots -->
            <div class="header-bar">
                <div class="header-dots">
                    <div class="dot dot-gray"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-red"></div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <!-- Page title and breadcrumb -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="page-title mb-0">
                            <i class="fas fa-money-bill-wave"></i> Contas a receber
                        </h4>
                        <nav aria-label="breadcrumb" class="breadcrumb-custom">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Início</a></li>
                                <li class="breadcrumb-item active">Contas a receber</li>
                                <li class="breadcrumb-item active">Listar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                
                <!-- Action buttons and date selection -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <button class="btn btn-success">
                            <i class="fas fa-plus"></i> Adicionar
                        </button>
                        <div class="btn-group ms-2">
                            <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i> Mais ações
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="exportar-pdf.php">Exportar PDF</a></li>
                                <li><a class="dropdown-item" href="#">Imprimir</a></li>
                                <li><a class="dropdown-item" href="#">Configurações</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="month-selector me-3">
                            <span>Setembro de 2023 <i class="fas fa-calendar-alt ms-1"></i></span>
                        </div>
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-search"></i> Busca avançada
                        </button>
                    </div>
                </div>
                
                <!-- Status boxes -->
                <div class="row mb-4">
                    <div class="col">
                        <div class="status-box status-vencido">
                            <div>Vencidos</div>
                            <div class="value-cell"><?= Formatter::formatarMoeda($totais['vencidos']) ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="status-box status-vencendo">
                            <div>Vencendo</div>
                            <div class="value-cell"><?= Formatter::formatarMoeda($totais['vencendo']) ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="status-box status-a-vencer">
                            <div>A vencer</div>
                            <div class="value-cell"><?= Formatter::formatarMoeda($totais['a_vencer']) ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="status-box status-recebido">
                            <div>Recebidos</div>
                            <div class="value-cell"><?= Formatter::formatarMoeda($totais['recebidos']) ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="status-box badge-total">
                            <div>Total</div>
                            <div class="value-cell"><?= Formatter::formatarMoeda($totais['total']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Main table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>Entidade</th>
                                <th>Plano de contas</th>
                                <th>Pagamento</th>
                                <th>Data</th>
                                <th>Valor total</th>
                                <th>Situação</th>
                                <th>Lote</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contas as $conta): ?>
                            <tr>
                                <td><?= $conta['codigo'] ?></td>
                                <td><?= $conta['descricao'] ?>
                                    <?= $conta['verificado'] ? '<i class="fas fa-check-circle verified-icon"></i>' : '' ?>
                                </td>
                                <td><?= $conta['entidade'] ?></td>
                                <td><?= $conta['plano_conta'] ?></td>
                                <td><?= $conta['forma_pagamento'] ?></td>
                                <td><?= Formatter::formatarData($conta['data_vencimento']) ?></td>
                                <td class="value-cell"><?= Formatter::formatarMoeda($conta['valor_total']) ?></td>
                                <td><span class="badge <?= Formatter::getSituacaoBadgeClass($conta['situacao']) ?>">
                                    <?= ucfirst($conta['situacao']) ?>
                                </span></td>
                                <td><?= $conta['lote'] ?></td>
                                <td>
                                    <button class="btn action-btn btn-view" data-id="<?= $conta['id'] ?>"><i class="fas fa-eye"></i></button>
                                    <button class="btn action-btn btn-edit" data-id="<?= $conta['id'] ?>"><i class="fas fa-pencil-alt"></i></button>
                                    <button class="btn action-btn btn-delete" data-id="<?= $conta['id'] ?>"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/contas.js"></script>
</body>
</html>
