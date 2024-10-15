INSERT INTO users (name, email, password, foto, admin) (
    SELECT
        name,
        email,
        password,
        foto,
        1
    FROM `kxsafe`.users
    JOIN `kxsafe`.pessoas
        ON pessoas.id = users.id_pessoa
);

UPDATE users SET id_aux = id;

INSERT INTO empresas (razao_social, nome_fantasia, cnpj, ie, email, telefone, tipo_contribuicao, tipo) VALUES
('R. A. G. FERREIRA & CIA LTDA', 'TARGET CLIENT ASSESSORIA & SOFTWARE (FRANQUEADORA)', '04503163000148', '189141310119', 'target@targetclient.com.br', '18981513030', 1, 1);

INSERT INTO empresas (razao_social, nome_fantasia, cnpj, ie, email, telefone, tipo_contribuicao, tipo) (
    SELECT
        razao_social,
        'TARGET CLIENT ASSESSORIA & SOFTWARE (FRANQUIA)',
        cnpj,
        ie,
        email,
        telefone,
        tipo_contribuicao,
        2
    FROM empresas
    WHERE id = 1
);

INSERT INTO empresas (razao_social, nome_fantasia, cnpj, ie, email, telefone, tipo_contribuicao, tipo) (
    SELECT
        razao_social,
        'TARGET CLIENT ASSESSORIA & SOFTWARE (CLIENTE)',
        cnpj,
        ie,
        email,
        telefone,
        tipo_contribuicao,
        3
    FROM empresas
    WHERE id = 1
);

INSERT INTO empresas (razao_social, nome_fantasia, cnpj, ie, email, telefone, tipo_contribuicao, tipo) (
    SELECT
        razao_social,
        'TARGET CLIENT ASSESSORIA & SOFTWARE (FORNECEDOR)',
        cnpj,
        ie,
        email,
        telefone,
        tipo_contribuicao,
        4
    FROM empresas
    WHERE id = 1
);

INSERT INTO empresas (razao_social, nome_fantasia, cnpj, tipo, id_criadora) (
    SELECT
        razao_social,
        CONCAT(nome_fantasia, ' (FRANQUIA)'),
        cnpj,
        2,
        1
    FROM `kxsafe`.empresas
    WHERE cnpj = '29009794000136'
);

INSERT INTO empresas (razao_social, nome_fantasia, cnpj, tipo, id_criadora, id_matriz) (
    SELECT
        razao_social,
        nome_fantasia,
        cnpj,
        2,
        5,
        5
    FROM `kxsafe`.empresas
    WHERE cnpj = '40652980000121'
);

INSERT INTO empresas (razao_social, nome_fantasia, cnpj, tipo, id_criadora) (
    SELECT
        razao_social,
        CONCAT(nome_fantasia, ' (CLIENTE)'),
        cnpj,
        3,
        2
    FROM `kxsafe`.empresas
    WHERE cnpj = '29009794000136'
);

INSERT INTO empresas (razao_social, nome_fantasia, cnpj, tipo, id_criadora, id_matriz) (
    SELECT
        razao_social,
        nome_fantasia,
        cnpj,
        3,
        2,
        7
    FROM `kxsafe`.empresas
    WHERE cnpj NOT IN (
        SELECT cnpj
        FROM empresas
    )
);

INSERT INTO empresas_usuarios (id_empresa, id_usuario) (
    SELECT
        users_novo.id,
        empresas_novo.id

    FROM `kxsafe`.users

    LEFT JOIN `kxsafe`.pessoas
        ON pessoas.id = users.id_pessoa

    LEFT JOIN `kxsafe`.empresas
        ON empresas.id = pessoas.id_empresa

    LEFT JOIN users AS users_novo
        ON users_novo.email = users.email

    LEFT JOIN empresas AS empresas_novo
        ON empresas_novo.cnpj = empresas.cnpj
);

INSERT INTO empresas_usuarios (id_empresa, id_usuario) (
    SELECT
        tab_empresas.id AS id_empresa,
        tab_usuarios.id AS id_usuario

    FROM (
        SELECT id_aux AS id
        FROM users
        WHERE email = 'eliszangela@rcplast.com.br' 
    ) AS tab_usuarios

    CROSS JOIN (
        SELECT id
        FROM empresas
        WHERE cnpj IN ('20610392000118', '29009794000136')
    ) AS tab_empresas
);

DELETE FROM empresas_usuarios WHERE id IN (
    SELECT id
    FROM (
        SELECT
            MIN(id) AS id,
            id_empresa,
            id_usuario
        FROM empresas_usuarios
        GROUP BY
            id_empresa,
            id_usuario
        HAVING COUNT(id) > 1
    ) AS tab
);

INSERT INTO setores(descr, lixeira) (
    SELECT
        descr,
        lixeira
    FROM `kxsafe`.setores
);

ALTER TABLE funcionarios ADD COLUMN id_ant INT;
INSERT INTO funcionarios(nome, cpf, funcao, admissao, senha, foto, id_empresa, id_setor, lixeira, id_ant) (
    SELECT
        nome,
        cpf,
        funcao,
        admissao,
        senha,
        foto,
        empresas_novo.id,
        id_setor,
        pessoas.lixeira,
        pessoas.id

    FROM `kxsafe`.pessoas

    LEFT JOIN `kxsafe`.users
        ON users.id_pessoa = pessoas.id

    LEFT JOIN `kxsafe`.empresas
        ON pessoas.id_empresa = empresas.id

    LEFT JOIN (
        SELECT
            MIN(id) AS id,
            cnpj
        FROM empresas
        GROUP BY cnpj
    ) AS empresas_novo ON empresas_novo.cnpj = empresas.cnpj

    WHERE users.id IS NULL
);

UPDATE funcionarios SET senha = 1234 WHERE senha < 1000;

ALTER TABLE categorias ADD COLUMN id_ant INT;
INSERT INTO categorias (id_ant, descr, lixeira) (
    SELECT
        id,
        descr,
        lixeira
    FROM `kxsafe`.valores
    WHERE alias = 'categorias'
);

INSERT INTO itens (descr, preco, referencia, tamanho, validade, ca, validade_ca, detalhes, foto, cod_externo, cod_ou_id, consumo, lixeira, id_categoria) (
    SELECT
        produtos.descr,
        preco,
        referencia,
        tamanho,
        validade,
        ca,
        validade_ca,
        detalhes,
        foto,
        cod_externo,
        CASE
            WHEN (cod_externo IS NOT NULL AND TRIM(cod_externo) <> '') THEN cod_externo
            ELSE CAST(produtos.id AS CHAR)
        END,
        consumo,
        produtos.lixeira,
        categorias.id

    FROM `kxsafe`.produtos

    LEFT JOIN categorias
        ON categorias.id_ant = produtos.id_categoria
);

ALTER TABLE categorias DROP COLUMN id_ant;

INSERT INTO atribuicoes (funcionario_ou_setor_chave, funcionario_ou_setor_valor, produto_ou_referencia_chave, produto_ou_referencia_valor, qtd, validade, obrigatorio, lixeira) (
    SELECT
        CASE
            WHEN pessoa_ou_setor_chave = 'S' THEN 'S'
            ELSE 'F'
        END,
        CASE
            WHEN pessoa_ou_setor_chave = 'S' THEN pessoa_ou_setor_valor
            ELSE funcionarios.id
        END,
        produto_ou_referencia_chave,
        produto_ou_referencia_valor,
        qtd,
        validade,
        obrigatorio,
        atribuicoes.lixeira
    FROM `kxsafe`.atribuicoes
    LEFT JOIN funcionarios
        ON funcionarios.id_ant = pessoa_ou_setor_valor AND pessoa_ou_setor_chave = 'P'
);

ALTER TABLE funcionarios DROP COLUMN id_ant;

DELETE FROM empresas_usuarios WHERE id_usuario IS NULL;

INSERT INTO empresas_usuarios (id_empresa, id_usuario) (
    SELECT
        id,
        1
    FROM empresas
    WHERE cnpj = '04503163000148' OR id IN (
        SELECT id_matriz
        FROM empresas
        WHERE cnpj = '04503163000148'
    ) OR id IN (
        SELECT id_criadora
        FROM empresas
        WHERE cnpj = '04503163000148'
    )
);

DELETE FROM empresas_usuarios WHERE id_usuario IN (7,8);

INSERT INTO empresas_usuarios (id_empresa, id_usuario) (
    SELECT
        id,
        7
    FROM empresas
    WHERE (cnpj = '29009794000136' OR id IN (
        SELECT id_matriz
        FROM empresas
        WHERE cnpj = '29009794000136'
    ) OR id IN (
        SELECT id_criadora
        FROM empresas
        WHERE cnpj = '29009794000136'
    )) AND id NOT IN (1,2)
);

INSERT INTO empresas_usuarios (id_empresa, id_usuario) (
    SELECT
        id,
        8
    FROM empresas
    WHERE (cnpj = '29009794000136' OR id IN (
        SELECT id_matriz
        FROM empresas
        WHERE cnpj = '29009794000136'
    ) OR id IN (
        SELECT id_criadora
        FROM empresas
        WHERE cnpj = '29009794000136'
    )) AND id NOT IN (1,2)
);

UPDATE empresas SET id_matriz = 0 WHERE id_matriz IS NULL;