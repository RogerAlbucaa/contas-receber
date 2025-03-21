$(document).ready(function() {
    // Armazena o estado de visibilidade das contas
    const contasOcultas = new Set();
    
    // Manipula o clique no botão de visualização
    $('.btn-view').on('click', function() {
        const row = $(this).closest('tr');
        const contaId = $(this).data('id');
        
        // Toggle da classe e do ícone
        if (contasOcultas.has(contaId)) {
            revelarConta(row, contaId);
        } else {
            ocultarConta(row, contaId);
        }
    });
    
    function ocultarConta(row, contaId) {
        // Oculta os dados substituindo por asteriscos
        row.find('td:not(:last-child)').each(function() {
            const td = $(this);
            // Guarda o texto original como data attribute
            td.attr('data-original', td.html());
            // Substitui o conteúdo por asteriscos mantendo o mesmo comprimento
            const length = td.text().trim().length;
            td.html('*'.repeat(length > 0 ? length : 1));
        });
        
        // Atualiza o ícone do botão
        row.find('.btn-view i')
           .removeClass('fa-eye')
           .addClass('fa-eye-slash');
           
        // Marca a conta como oculta
        contasOcultas.add(contaId);
    }
    
    function revelarConta(row, contaId) {
        // Restaura os dados originais
        row.find('td:not(:last-child)').each(function() {
            const td = $(this);
            td.html(td.attr('data-original'));
            td.removeAttr('data-original');
        });
        
        // Atualiza o ícone do botão
        row.find('.btn-view i')
           .removeClass('fa-eye-slash')
           .addClass('fa-eye');
           
        // Remove a conta da lista de ocultas
        contasOcultas.delete(contaId);
    }

    // Função para ordenar a tabela
    $('#orderBy').on('change', function() {
        const orderBy = $(this).val();
        const tbody = $('.table tbody');
        const rows = tbody.find('tr').get();

        if (!orderBy) {
            window.location.reload();
            return;
        }

        rows.sort(function(a, b) {
            let aValue, bValue;

            switch (orderBy) {
                case 'data':
                    // Converter data DD/MM/YYYY para formato ordenável
                    aValue = $(a).find('td:eq(5)').text().split('/').reverse().join('');
                    bValue = $(b).find('td:eq(5)').text().split('/').reverse().join('');
                    // Ordenar decrescente (mais recentes primeiro)
                    return bValue.localeCompare(aValue);

                case 'valor':
                    // Converter valor em número removendo formatação
                    aValue = parseFloat($(a).find('td:eq(6)').text().replace('R$', '').replace('.', '').replace(',', '.'));
                    bValue = parseFloat($(b).find('td:eq(6)').text().replace('R$', '').replace('.', '').replace(',', '.'));
                    // Ordenar decrescente (maiores valores primeiro)
                    return bValue - aValue;

                case 'situacao':
                    // Prioridade das situações
                    const prioridade = {
                        'Pendente': 1,
                        'Vencendo': 2,
                        'Vencido': 3,
                        'Recebido': 4,
                        'Cancelado': 5
                    };
                    aValue = prioridade[$(a).find('td:eq(7)').text().trim()] || 999;
                    bValue = prioridade[$(b).find('td:eq(7)').text().trim()] || 999;
                    return aValue - bValue;
            }
        });

        // Reinsere as linhas ordenadas na tabela
        tbody.empty();
        $.each(rows, function(index, row) {
            tbody.append(row);
        });

        // Mostrar toast de confirmação com mensagem específica
        const mensagens = {
            'data': 'Contas ordenadas por data (mais recentes primeiro)',
            'valor': 'Contas ordenadas por valor (maiores valores primeiro)',
            'situacao': 'Contas ordenadas por situação (prioridade para pendentes)'
        };

        const toast = new bootstrap.Toast($('#liveToast'));
        $('#toastTitle').text('Lista Ordenada');
        $('#toastMessage').text(mensagens[orderBy]);
        $('#liveToast').removeClass('error').addClass('success');
        toast.show();
    });
});
