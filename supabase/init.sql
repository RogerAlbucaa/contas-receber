-- Criar tipo enum para situação das contas
create type situacao_conta as enum ('pendente', 'recebido', 'cancelado', 'vencido', 'vencendo');

-- Tabela de entidades (clientes/fornecedores)
create table entidades (
    id uuid default gen_random_uuid() primary key,
    nome varchar(100) not null,
    tipo varchar(20) not null, -- pessoa_fisica, pessoa_juridica
    documento varchar(20), -- CPF ou CNPJ
    created_at timestamp with time zone default now(),
    updated_at timestamp with time zone default now()
);

-- Tabela de plano de contas
create table plano_contas (
    id uuid default gen_random_uuid() primary key,
    descricao varchar(100) not null,
    tipo varchar(20) not null, -- receita, despesa
    created_at timestamp with time zone default now(),
    updated_at timestamp with time zone default now()
);

-- Tabela de formas de pagamento
create table formas_pagamento (
    id uuid default gen_random_uuid() primary key,
    descricao varchar(50) not null,
    ativo boolean default true,
    created_at timestamp with time zone default now(),
    updated_at timestamp with time zone default now()
);

-- Tabela principal de contas a receber
create table contas_receber (
    id uuid default gen_random_uuid() primary key,
    codigo varchar(20) not null,
    descricao varchar(200) not null,
    entidade_id uuid references entidades(id),
    plano_conta_id uuid references plano_contas(id),
    forma_pagamento_id uuid references formas_pagamento(id),
    data_emissao date not null,
    data_vencimento date not null,
    data_recebimento date,
    valor_total decimal(10,2) not null,
    situacao situacao_conta not null default 'pendente',
    lote varchar(50),
    verificado boolean default false,
    observacao text,
    created_at timestamp with time zone default now(),
    updated_at timestamp with time zone default now()
);

-- Trigger para atualizar updated_at
create or replace function update_updated_at_column()
returns trigger as $$
begin
    new.updated_at = now();
    return new;
end;
$$ language plpgsql;

-- Aplicar trigger em todas as tabelas
create trigger update_entidades_updated_at
    before update on entidades
    for each row
    execute function update_updated_at_column();

create trigger update_plano_contas_updated_at
    before update on plano_contas
    for each row
    execute function update_updated_at_column();

create trigger update_formas_pagamento_updated_at
    before update on formas_pagamento
    for each row
    execute function update_updated_at_column();

create trigger update_contas_receber_updated_at
    before update on contas_receber
    for each row
    execute function update_updated_at_column();

-- Inserir alguns dados iniciais
insert into formas_pagamento (descricao) values
    ('PIX'),
    ('Boleto'),
    ('Dinheiro'),
    ('Cartão de Crédito'),
    ('Transferência Bancária');

insert into plano_contas (descricao, tipo) values
    ('Vendas Balcão', 'receita'),
    ('Serviços', 'receita'),
    ('Consultorias', 'receita'),
    ('Vendas Online', 'receita');

-- Criar view para facilitar consultas
create view vw_contas_receber as
select 
    cr.id,
    cr.codigo,
    cr.descricao,
    e.nome as entidade,
    pc.descricao as plano_conta,
    fp.descricao as forma_pagamento,
    cr.data_emissao,
    cr.data_vencimento,
    cr.data_recebimento,
    cr.valor_total,
    cr.situacao,
    cr.lote,
    cr.verificado
from contas_receber cr
left join entidades e on e.id = cr.entidade_id
left join plano_contas pc on pc.id = cr.plano_conta_id
left join formas_pagamento fp on fp.id = cr.forma_pagamento_id;
