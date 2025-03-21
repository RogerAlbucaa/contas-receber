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
});
