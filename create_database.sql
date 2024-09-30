CREATE DATABASE crud_bloco_notas_cand_bari;
USE crud_bloco_notas_cand_bari;

CREATE TABLE usuarios(
	id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_usuario VARCHAR(45) NOT NULL,
    senha_usuario VARCHAR(45) NOT NULL
);

CREATE TABLE categoria(
	id_categoria INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_categoria VARCHAR(45)
);

CREATE TABLE notas(
	id_nota INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titulo_nota VARCHAR(255),
    conteudo_nota VARCHAR(1000)
);