CREATE DATABASE kxsafe2;
USE kxsafe2;

CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED NOT NULL,
    uuid VARCHAR(255) NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()
);

ALTER TABLE failed_jobs
    ADD PRIMARY KEY (id),
    ADD UNIQUE KEY failed_jobs_uuid_unique (uuid);

ALTER TABLE failed_jobs
    MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE migrations (
    id INT(10) UNSIGNED NOT NULL,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
);

INSERT INTO migrations (id, migration, batch) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

ALTER TABLE migrations ADD PRIMARY KEY (id);

ALTER TABLE migrations MODIFY id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL
);

ALTER TABLE password_resets ADD PRIMARY KEY (email);

CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED NOT NULL,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL,
    abilities TEXT DEFAULT NULL,
    last_used_at TIMESTAMP NULL DEFAULT NULL,
    expires_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

ALTER TABLE personal_access_tokens
    ADD PRIMARY KEY (id),
    ADD UNIQUE KEY personal_access_tokens_token_unique (token),
    ADD KEY personal_access_tokens_tokenable_type_tokenable_id_index (tokenable_type, tokenable_id);

ALTER TABLE personal_access_tokens MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE users (
    id BIGINT UNSIGNED NOT NULL,
    id_aux INT,
	id_empresa INT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    foto VARCHAR(256),
    remember_token VARCHAR(100) DEFAULT NULL,
    admin TINYINT,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

ALTER TABLE users
    ADD PRIMARY KEY (id),
    ADD UNIQUE KEY users_email_unique (email),
    ADD UNIQUE cod (id_aux);

ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE grupos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
    lixeira TINYINT DEFAULT 0,
    id_empresa INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE segmentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
    lixeira TINYINT DEFAULT 0,
    id_empresa INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE cep (
	id INT AUTO_INCREMENT PRIMARY KEY,
	cod VARCHAR(16),
	logradouro_tipo VARCHAR(8),
	logradouro_tipo_abv VARCHAR(4),
	logradouro_descr VARCHAR(32),
	logradouro_intervalo_min VARCHAR(8),
	logradouro_intervalo_max VARCHAR(8),
	cod_ibge_uf VARCHAR(2),
	cod_ibge_cidade VARCHAR(8),
	cidade VARCHAR(32),
	bairro VARCHAR(32),
	estado VARCHAR(32),
	uf VARCHAR(2),
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
	razao_social VARCHAR(128),
	nome_fantasia VARCHAR(64),
	cnpj VARCHAR(32),
    ie VARCHAR(16),
    email VARCHAR(32),
    telefone VARCHAR(16),
    tipo_contribuicao INT, -- 1-Sim, 2-Não, 3-Isento
    tipo INT, -- 1-Franqueadora, 2-Franquia, 3-Cliente, 4-Fornecedor
	royalties NUMERIC(8,2),
	lixeira TINYINT DEFAULT 0,
    id_grupo INT,
    id_segmento INT,
	id_matriz INT,
	id_criadora INT,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_grupo) REFERENCES grupos(id),
    FOREIGN KEY (id_segmento) REFERENCES segmentos(id)
);

CREATE TABLE enderecos (
	id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(8),
	complemento VARCHAR(32),
    referencia VARCHAR(64),
	id_empresa INT,
	id_cep INT,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (id_empresa) REFERENCES empresas(id),
	FOREIGN KEY (id_cep) REFERENCES cep(id)
);

ALTER TABLE empresas
	ADD FOREIGN KEY (id_matriz) REFERENCES empresas(id),
	ADD FOREIGN KEY (id_criadora) REFERENCES empresas(id);
ALTER TABLE grupos ADD FOREIGN KEY (id_empresa) REFERENCES empresas(id);
ALTER TABLE segmentos ADD FOREIGN KEY (id_empresa) REFERENCES empresas(id);
ALTER TABLE users ADD FOREIGN KEY (id_empresa) REFERENCES empresas(id);

CREATE TABLE empresas_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT,
    id_usuario INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id),
    FOREIGN KEY (id_usuario) REFERENCES users(id_aux)
);

CREATE TABLE setores (
	id INT AUTO_INCREMENT PRIMARY KEY,
	descr VARCHAR(32),
	lixeira TINYINT DEFAULT 0,
    id_empresa INT,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

CREATE TABLE funcionarios (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(64),
	cpf VARCHAR(16),
	funcao VARCHAR(64),
	admissao DATE,
	senha INT,
	foto VARCHAR(512),
	telefone VARCHAR(16),
	email VARCHAR(32),
	pis VARCHAR(16),
	supervisor TINYINT DEFAULT 0,
    lixeira TINYINT DEFAULT 0,
	id_empresa INT,
    id_setor INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (id_empresa) REFERENCES empresas(id),
	FOREIGN KEY (id_setor) REFERENCES setores(id)
);

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
    lixeira TINYINT DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE itens (
	id INT AUTO_INCREMENT PRIMARY KEY,
	descr VARCHAR(256),
	preco NUMERIC(8,2),
    referencia VARCHAR(64),
	tamanho VARCHAR(32),
	validade INT,
	ca VARCHAR(16),
	validade_ca DATE,
    detalhes TEXT,
    foto VARCHAR(512),
	cod_externo VARCHAR(8),
    cod_ou_id VARCHAR(8),
	consumo TINYINT,
    lixeira TINYINT DEFAULT 0,
    id_categoria INT,
    id_fornecedor INT,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id),
    FOREIGN KEY (id_fornecedor) REFERENCES empresas(id)
);

ALTER TABLE itens
    ADD UNIQUE cod_ou_id (cod_ou_id(8)),
    ADD UNIQUE referencia (referencia(64), tamanho(32));
	
CREATE TABLE precos (
	id INT AUTO_INCREMENT PRIMARY KEY,
	preco NUMERIC(8,2),
	id_cliente INT,
	id_item INT,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (id_cliente) REFERENCES empresas(id),
	FOREIGN KEY (id_item) REFERENCES itens(id)
);

CREATE TABLE atribuicoes (
	id INT AUTO_INCREMENT PRIMARY KEY,
	funcionario_ou_setor_chave CHAR,
	funcionario_ou_setor_valor INT,
	produto_ou_referencia_chave CHAR,
	produto_ou_referencia_valor VARCHAR(64),
	qtd NUMERIC(10,5),
	validade INT,
	obrigatorio TINYINT DEFAULT 0,
	lixeira TINYINT DEFAULT 0,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (funcionario_ou_setor_valor) REFERENCES funcionarios(id),
	FOREIGN KEY (funcionario_ou_setor_valor) REFERENCES setores(id),
	FOREIGN KEY (produto_ou_referencia_valor) REFERENCES itens(cod_ou_id),
	FOREIGN KEY (produto_ou_referencia_valor) REFERENCES itens(referencia)
);

CREATE TABLE naturezas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
	lixeira TINYINT DEFAULT 0,
    id_empresa INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

CREATE TABLE cfop (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cfop VARCHAR(4),
    descr VARCHAR(128),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE condicoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
	lixeira TINYINT DEFAULT 0,
    id_empresa INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

CREATE TABLE formas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
    intervalo INT,
    vtaxa NUMERIC(6,2),
    ptaxa NUMERIC(8,5),
	lixeira TINYINT DEFAULT 0,
    id_condicao INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_condicao) REFERENCES condicoes(id)
);

CREATE TABLE notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    es CHAR,
    produtos NUMERIC(8,2),
	servicos NUMERIC(8,2),
	acrescimo NUMERIC(8,2),
    desconto NUMERIC(8,2),
	impostos NUMERIC(8,2),
	liquido NUMERIC(8,2),
    id_natureza INT,
	id_emitente INT,
    id_cliente INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_emitente) REFERENCES empresas(id),
    FOREIGN KEY (id_cliente) REFERENCES empresas(id),
	FOREIGN KEY (id_natureza) REFERENCES naturezas(id)
);

CREATE TABLE nota_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qtd NUMERIC(10,5),
    valor NUMERIC(8,2),
    acrescimo NUMERIC(8,2),
    desconto NUMERIC(8,2),
	impostos NUMERIC(8,2),
	liquido NUMERIC(8,2),
    id_cfop INT,
	id_item INT,
    id_nota INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cfop) REFERENCES cfop(id),
	FOREIGN KEY (id_item) REFERENCES itens(id),
    FOREIGN KEY (id_nota) REFERENCES notas(id)
);

CREATE TABLE impostos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
    sigla VARCHAR(8),
    porcentagem NUMERIC(10,5),
    valor NUMERIC(10,5),
    id_ni INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ni) REFERENCES nota_itens(id)
);

CREATE TABLE bancos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cod VARCHAR(4),
    descr VARCHAR(32),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE contas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
    agencia VARCHAR(8),
    conta VARCHAR(16),
    pix VARCHAR(128),
	cedente VARCHAR(128),
	nossnum VARCHAR(16),
	lixeira TINYINT DEFAULT 0,
    id_banco INT,
    id_empresa INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id),
    FOREIGN KEY (id_banco) REFERENCES bancos(id)
);

CREATE TABLE tpdoc (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
	lixeira TINYINT DEFAULT 0,
    id_empresa INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

CREATE TABLE tpbxa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
    caixa TINYINT,
	lixeira TINYINT DEFAULT 0,
    id_empresa INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

CREATE TABLE planos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
    rp CHAR,
	lixeira TINYINT DEFAULT 0,
    id_empresa INT,
    id_pai INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

ALTER TABLE planos ADD FOREIGN KEY (id_pai) REFERENCES planos(id);

CREATE TABLE faturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rp CHAR,
	valor NUMERIC(8,2),
    emissao DATE,
    vencimento DATE,
	ndoc INT,
	parcela INT,
	pago NUMERIC(8,2),
    id_tpdoc INT,
    id_nota INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tpdoc) REFERENCES tpdoc(id),
    FOREIGN KEY (id_nota) REFERENCES notas(id)
);

CREATE TABLE titulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
	rp CHAR,
    valor NUMERIC(8,2),
    emissao DATE,
    vencimento DATE,
	ndoc INT,
	parcela INT,
	pago NUMERIC(8,2),
	obs VARCHAR(128),
	nossnum VARCHAR(16),
	digitavel VARCHAR(64),
    id_tpdoc INT,
    id_fatura INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tpdoc) REFERENCES tpdoc(id),
    FOREIGN KEY (id_fatura) REFERENCES faturas(id)
);

CREATE TABLE baixas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valor NUMERIC(8,2),
	acrescimo NUMERIC(8,2),
	desconto NUMERIC(8,2),
	total NUMERIC(8,2),
    id_titulo INT,
	id_forma INT,
	id_tpbxa INT,
	id_plano INT,
    id_conta INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_titulo) REFERENCES titulos(id),
	FOREIGN KEY (id_forma) REFERENCES formas(id),
	FOREIGN KEY (id_tpbxa) REFERENCES tpbxa(id),
	FOREIGN KEY (id_plano) REFERENCES planos(id),
    FOREIGN KEY (id_conta) REFERENCES contas(id)
);

CREATE TABLE movimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    valor NUMERIC(8,2),
	consolidado TINYINT,
	acrescimo NUMERIC(8,2),
	desconto NUMERIC(8,2),
	total NUMERIC(8,2),
    id_baixa INT,
    id_plano INT,
	id_conta INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_baixa) REFERENCES baixas(id),
	FOREIGN KEY (id_plano) REFERENCES planos(id), 
	FOREIGN KEY (id_conta) REFERENCES contas(id)
);

CREATE TABLE locais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
	lixeira TINYINT DEFAULT 0,
    id_empresa INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empresa) REFERENCES empresas(id)
);

CREATE TABLE locais_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
	descr VARCHAR(16),
	minimo NUMERIC(10,5),
	maximo NUMERIC(10,5),
	id_local INT,
	id_item INT,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (id_local) REFERENCES locais(id),
	FOREIGN KEY (id_item) REFERENCES itens(id)
);

CREATE TABLE maquinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
	lixeira TINYINT DEFAULT 0,
    id_local INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_local) REFERENCES locais(id)
);

CREATE TABLE retiradas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qtd NUMERIC(10,5),
	data DATE,
    id_local INT,
	id_maquina INT,
    id_produto INT,
    id_atribuicao INT,
	id_nota INT,
	id_funcionario INT,
    id_supervisor INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_local) REFERENCES locais(id),
	FOREIGN KEY (id_maquina) REFERENCES maquinas(id),
    FOREIGN KEY (id_produto) REFERENCES itens(id),
    FOREIGN KEY (id_atribuicao) REFERENCES atribuicoes(id),
	FOREIGN KEY (id_nota) REFERENCES notas(id),
	FOREIGN KEY (id_funcionario) REFERENCES funcionarios(id),
    FOREIGN KEY (id_supervisor) REFERENCES funcionarios(id)
);

CREATE TABLE estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    qtd NUMERIC(10,5),
	valor NUMERIC(8,2),
	data DATE,
    es CHAR,
    descr VARCHAR(32),
    id_li INT,
    id_ni INT,
	id_fornecedor INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_li) REFERENCES locais_itens(id),
    FOREIGN KEY (id_ni) REFERENCES nota_itens(id),
    FOREIGN KEY (id_fornecedor) REFERENCES empresas(id)
);

CREATE TABLE concessoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inicio NUMERIC(8,2),
    id_franqueadora INT,
    id_franquia INT,
    id_maquina INT,
    id_nota INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_franqueadora) REFERENCES empresas(id),
    FOREIGN KEY (id_franquia) REFERENCES empresas(id),
    FOREIGN KEY (id_maquina) REFERENCES maquinas(id),
    FOREIGN KEY (id_nota) REFERENCES notas(id)
);

CREATE TABLE log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tabela VARCHAR(32),
	nome VARCHAR(32),
    acao CHAR,
    fk INT,
    id_usuario INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fk) REFERENCES concessoes(id),
    FOREIGN KEY (fk) REFERENCES empresas(id),
    FOREIGN KEY (fk) REFERENCES empresas_usuarios(id),
    FOREIGN KEY (fk) REFERENCES funcionarios(id),
    FOREIGN KEY (fk) REFERENCES grupos(id),
    FOREIGN KEY (fk) REFERENCES segmentos(id),
    FOREIGN KEY (fk) REFERENCES setores(id),
    FOREIGN KEY (fk) REFERENCES atribuicoes(id),
    FOREIGN KEY (fk) REFERENCES categorias(id),
    FOREIGN KEY (fk) REFERENCES estoque(id),
    FOREIGN KEY (fk) REFERENCES itens(id),
    FOREIGN KEY (fk) REFERENCES locais(id),
    FOREIGN KEY (fk) REFERENCES locais_itens(id),
    FOREIGN KEY (fk) REFERENCES maquinas(id),
	FOREIGN KEY (fk) REFERENCES precos(id),
    FOREIGN KEY (fk) REFERENCES retiradas(id),
    FOREIGN KEY (fk) REFERENCES condicoes(id),
	FOREIGN KEY (fk) REFERENCES faturas(id),
    FOREIGN KEY (fk) REFERENCES impostos(id),
    FOREIGN KEY (fk) REFERENCES naturezas(id),
    FOREIGN KEY (fk) REFERENCES nota_itens(id),
    FOREIGN KEY (fk) REFERENCES notas(id),
    FOREIGN KEY (fk) REFERENCES cfop(id),
    FOREIGN KEY (fk) REFERENCES baixas(id),
    FOREIGN KEY (fk) REFERENCES bancos(id),
    FOREIGN KEY (fk) REFERENCES contas(id),
    FOREIGN KEY (fk) REFERENCES formas(id),
    FOREIGN KEY (fk) REFERENCES movimentos(id),
    FOREIGN KEY (fk) REFERENCES planos(id),
    FOREIGN KEY (fk) REFERENCES titulos(id),
    FOREIGN KEY (fk) REFERENCES tpbxa(id),
    FOREIGN KEY (fk) REFERENCES tpdoc(id),
    FOREIGN KEY (fk) REFERENCES cep(id),
    FOREIGN KEY (id_usuario) REFERENCES users(id_aux)
);

CREATE TABLE modulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(16),
    ordem INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descr VARCHAR(32),
    url VARCHAR(128),
    ordem INT,
    id_pai INT,
    id_modulo INT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_modulo) REFERENCES modulos(id)
);

CREATE TABLE menu_perfis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_menu INT,
    tipo INT,
    admin TINYINT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_menu) REFERENCES menu(id)
);

ALTER TABLE menu ADD FOREIGN KEY (id_pai) REFERENCES menu(id);

CREATE TRIGGER user_id_aux AFTER INSERT ON users FOR EACH ROW UPDATE users SET id_aux = id;