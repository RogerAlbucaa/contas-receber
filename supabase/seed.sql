-- Inserir entidades
INSERT INTO entidades (nome, tipo, documento) VALUES
    ('Vendas', 'pessoa_juridica', '00000000000000'),
    ('INSS/CEI', 'pessoa_juridica', '00000000000000'),
    ('Vendas no balcão', 'pessoa_juridica', '00000000000000');

-- Inserir dados nas contas a receber
INSERT INTO contas_receber (
    codigo,
    descricao,
    entidade_id,
    plano_conta_id,
    forma_pagamento_id,
    data_emissao,
    data_vencimento,
    data_recebimento,
    valor_total,
    situacao,
    lote,
    verificado
) VALUES
    (
        '6654',
        'Venda do P.',
        (SELECT id FROM entidades WHERE nome = 'Vendas' LIMIT 1),
        (SELECT id FROM plano_contas WHERE descricao = 'Vendas Balcão' LIMIT 1),
        (SELECT id FROM formas_pagamento WHERE descricao = 'Dinheiro' LIMIT 1),
        '2023-09-01',
        '2023-09-01',
        '2023-09-01',
        3989.00,
        'recebido',
        'Serviços',
        true
    ),
    (
        '6674',
        'Venda do P.',
        (SELECT id FROM entidades WHERE nome = 'Vendas' LIMIT 1),
        (SELECT id FROM plano_contas WHERE descricao = 'Serviços' LIMIT 1),
        (SELECT id FROM formas_pagamento WHERE descricao = 'PIX' LIMIT 1),
        '2023-09-14',
        '2023-09-14',
        '2023-09-14',
        181.81,
        'recebido',
        'Serviços',
        true
    ),
    (
        '6653',
        'Venda do P.',
        (SELECT id FROM entidades WHERE nome = 'Vendas' LIMIT 1),
        (SELECT id FROM plano_contas WHERE descricao = 'Serviços' LIMIT 1),
        (SELECT id FROM formas_pagamento WHERE descricao = 'PIX' LIMIT 1),
        '2023-09-24',
        '2023-09-24',
        NULL,
        181.81,
        'cancelado',
        'Serviços',
        true
    ),
    (
        '6685',
        'INSS',
        (SELECT id FROM entidades WHERE nome = 'INSS/CEI' LIMIT 1),
        (SELECT id FROM plano_contas WHERE descricao = 'Serviços' LIMIT 1),
        (SELECT id FROM formas_pagamento WHERE descricao = 'Boleto' LIMIT 1),
        '2023-09-14',
        '2023-09-14',
        '2023-09-14',
        189.00,
        'recebido',
        'Serviços',
        true
    ),
    (
        '6690',
        'Venda de P. 2023',
        (SELECT id FROM entidades WHERE nome = 'Vendas no balcão' LIMIT 1),
        (SELECT id FROM plano_contas WHERE descricao = 'Vendas Balcão' LIMIT 1),
        (SELECT id FROM formas_pagamento WHERE descricao = 'Dinheiro' LIMIT 1),
        '2023-09-14',
        '2023-09-14',
        '2023-09-14',
        5.00,
        'recebido',
        'Serviços',
        true
    );
