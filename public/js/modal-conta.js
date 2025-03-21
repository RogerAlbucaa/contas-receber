$(document).ready(function() {
    const modal = new bootstrap.Modal(document.getElementById('contaModal'));
    let modoEdicao = false;
    let contaId = null;

    // Handler para o botão Adicionar
    $('.btn-adicionar').click(function() {
        modoEdicao = false;
        resetForm();
        $('#modalTitle').text('Adicionar Nova Conta');
        $('.btn-submit').text('Adicionar');
        modal.show();
    });

    // Handler para o botão Editar
    $('.btn-edit').click(function() {
        modoEdicao = true;
        contaId = $(this).data('id');
        $('#modalTitle').text('Editar Conta');
        $('.btn-submit').text('Salvar Alterações');
        carregarDadosConta(contaId);
        modal.show();
    });

    // Submit do formulário
    $('#formConta').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const dados = {
            descricao: formData.get('descricao'),
            entidade_id: formData.get('entidade_id'),
            plano_conta_id: formData.get('plano_conta_id'),
            forma_pagamento_id: formData.get('forma_pagamento_id'),
            data_vencimento: formData.get('data_vencimento'),
            valor_total: formData.get('valor_total'),
            situacao: formData.get('situacao'),
            lote: formData.get('lote'),
            data_emissao: new Date().toISOString().split('T')[0]
        };

        $.ajax({
            url: `${SUPABASE_CONFIG.url}/rest/v1/contas_receber`, // Isso não vai funcionar em um arquivo .js
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dados),
            headers: {
                'apikey': SUPABASE_CONFIG.key,
                'Authorization': `Bearer ${SUPABASE_CONFIG.key}`,
                'Content-Type': 'application/json',
                'Prefer': 'return=representation'
            },
            success: function(response) {
                modal.hide();
                window.location.reload();
            },
            error: function(xhr) {
                console.error('Erro:', xhr);
                mostrarErros(xhr.responseJSON || { error: 'Erro ao processar requisição' });
            }
        });
    });

    function carregarDadosConta(id) {
        $.ajax({
            url: `/contas-receber/rest/v1/contas_receber?id=eq.${id}`,
            method: 'GET',
            success: function(contas) {
                if (contas && contas.length > 0) {
                    const conta = contas[0];
                    $('#codigo').val(conta.codigo);
                    $('#descricao').val(conta.descricao);
                    $('#entidade_id').val(conta.entidade_id);
                    $('#plano_conta_id').val(conta.plano_conta_id);
                    $('#forma_pagamento_id').val(conta.forma_pagamento_id);
                    $('#data_vencimento').val(conta.data_vencimento);
                    $('#valor_total').val(conta.valor_total);
                    $('#situacao').val(conta.situacao);
                    $('#lote').val(conta.lote);
                }
            },
            error: function() {
                alert('Erro ao carregar dados da conta');
                modal.hide();
            }
        });
    }

    function resetForm() {
        $('#formConta')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Definir valores padrão para nova conta
        $('#situacao').val('pendente');
        $('#data_vencimento').val(new Date().toISOString().split('T')[0]);
    }

    function mostrarErros(errors) {
        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        if (typeof errors === 'object') {
            Object.keys(errors).forEach(field => {
                $(`#${field}`)
                    .addClass('is-invalid')
                    .after(`<div class="invalid-feedback">${errors[field]}</div>`);
            });
        } else {
            alert('Erro ao processar operação');
        }
    }
});
