CREATE DATABASE cbcontrol CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

USE cbcontrol;

CREATE TABLE IF NOT EXISTS marco_pessoa(
	id SMALLINT NOT NULL AUTO_INCREMENT,
	primeiro_nome VARCHAR(20) NOT NULL,
	ultimo_nome VARCHAR(20) NOT NULL,
	data_nascimento DATE,
	data_batismo DATE,
	telefone VARCHAR(12) NOT NULL,
	email VARCHAR(40),
	e_professor BOOLEAN NOT NULL DEFAULT FALSE,
	discipulador SMALLINT,
	CONSTRAINT PRIMARY KEY (id),
	CONSTRAINT FOREIGN KEY (discipulador) REFERENCES marco_pessoa (id),
	CONSTRAINT nao_duplic_pessoa UNIQUE (primeiro_nome, ultimo_nome, data_nascimento),
	CONSTRAINT CHECK (id <> discipulador)
);

CREATE TABLE IF NOT EXISTS marco_dom_espiritual(
	id TINYINT NOT NULL AUTO_INCREMENT,
	nome VARCHAR(25) NOT NULL UNIQUE,
	CONSTRAINT PRIMARY KEY (id),
	CONSTRAINT UNIQUE (nome)
);

CREATE TABLE IF NOT EXISTS marco_classe(
	id SMALLINT NOT NULL AUTO_INCREMENT,
	nome VARCHAR(40) NOT NULL,
	CONSTRAINT PRIMARY KEY (id),
	CONSTRAINT UNIQUE (nome)
);

CREATE TABLE IF NOT EXISTS marco_matricula(
	id SMALLINT NOT NULL AUTO_INCREMENT,
	pessoa_id SMALLINT NOT NULL,	
	classe_id SMALLINT NOT NULL,
	esta_cursando BOOLEAN NOT NULL DEFAULT TRUE,
	data_entrada DATE,
	data_saida DATE,
	CONSTRAINT PRIMARY KEY (id),
	CONSTRAINT FOREIGN KEY (pessoa_id) REFERENCES marco_pessoa (id)
		ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (classe_id) REFERENCES marco_classe (id)
		ON DELETE RESTRICT,
	CONSTRAINT UNIQUE (pessoa_id, classe_id, data_entrada)
);

CREATE TABLE IF NOT EXISTS marco_aula(
	id MEDIUMINT NOT NULL AUTO_INCREMENT,
	`data` DATE NOT NULL,
	classe_id SMALLINT NOT NULL,
	num_licao TINYINT NOT NULL,
	professor_id SMALLINT,
	estudo_licao BOOLEAN,
	pequeno_grupo BOOLEAN,
	estudo_biblico BOOLEAN,
	ativ_missionarias BOOLEAN,
	CONSTRAINT PRIMARY KEY (id),
	CONSTRAINT FOREIGN KEY (classe_id) REFERENCES marco_classe (id)
		ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (professor_id) REFERENCES marco_pessoa (id)
		ON DELETE SET NULL,
	CONSTRAINT CHECK (num_licao >= 0)
);

CREATE TABLE IF NOT EXISTS marco_doacao(
	`data` DATE,
	produto VARCHAR(30) NOT NULL,
	unidade VARCHAR(20) NOT NULL,
	quantidade SMALLINT NOT NULL,
	pessoa SMALLINT,
	CONSTRAINT FOREIGN KEY (pessoa) REFERENCES marco_pessoa (id),
	CONSTRAINT CHECK (quantidade > 0)
);

CREATE TABLE IF NOT EXISTS marco_presenca(
	aula_id MEDIUMINT NOT NULL,
	matricula_id SMALLINT NOT NULL,
	CONSTRAINT PRIMARY KEY (aula_id, matricula_id),
	CONSTRAINT FOREIGN KEY (aula_id) REFERENCES marco_aula (id)
		ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (matricula_id) REFERENCES marco_matricula (id)
		ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS marco_pessoa_dom(
	pessoa_id SMALLINT NOT NULL,
	dom_id TINYINT NOT NULL,
	CONSTRAINT PRIMARY KEY (pessoa_id, dom_id),
	CONSTRAINT FOREIGN KEY (pessoa_id) REFERENCES marco_pessoa (id)
		ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY (dom_id) REFERENCES marco_dom_espiritual (id)
		ON DELETE CASCADE
);