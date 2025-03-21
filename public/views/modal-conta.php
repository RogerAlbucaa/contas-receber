<?php
// Carregar dados necessários para o formulário
try {
    $contasService = new App\Services\ContasReceberService();
    $entidades = $contasService->getEntidades();
    $planoContas = $contasService->getPlanoContas();
    $formasPagamento = $contasService->getFormasPagamento();
} catch (Exception $e) {
    die("Erro ao carregar dados do formulário: " . $e->getMessage());
}
?>

<div class="modal fade" id="contaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Adicionar Nova Conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formConta">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Entidade</label>
                            <select class="form-select" id="entidade_id" name="entidade_id" required>
                                <option value="">Selecione uma entidade</option>
                                <?php foreach($entidades as $entidade): ?>
                                    <option value="<?= $entidade['id'] ?>"><?= $entidade['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Plano de Contas</label>
                            <select class="form-select" id="plano_conta_id" name="plano_conta_id" required>
                                <?php foreach($planoContas as $plano): ?>
                                    <option value="<?= $plano['id'] ?>"><?= $plano['descricao'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Forma de Pagamento</label>
                            <select class="form-select" id="forma_pagamento_id" name="forma_pagamento_id" required>
                                <?php foreach($formasPagamento as $forma): ?>
                                    <option value="<?= $forma['id'] ?>"><?= $forma['descricao'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data de Vencimento</label>
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valor Total</label>
                            <input type="number" step="0.01" class="form-control" id="valor_total" name="valor_total" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Situação</label>
                            <select class="form-select" id="situacao" name="situacao" required>
                                <option value="pendente">Pendente</option>
                                <option value="recebido">Recebido</option>
                                <option value="vencido">Vencido</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lote</label>
                            <input type="text" class="form-control" id="lote" name="lote">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formConta" class="btn btn-primary btn-submit">Salvar</button>
            </div>
        </div>
    </div>
</div>
