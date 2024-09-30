CREATE DATABASE crud_bloco_notas_bari_cand;
USE crud_bloco_notas_bari_cand;

CREATE TABLE usuarios(
	id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_usuario VARCHAR(45) NOT NULL,
    senha_usuario VARCHAR(45) NOT NULL
);

CREATE TABLE categorias(
	id_categoria INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_categoria VARCHAR(45)
);

CREATE TABLE notas(
	id_nota INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    titulo_nota VARCHAR(255),
    conteudo_nota VARCHAR(1000),
    data_criacao_nota DATE,
    fk_usuario INT NOT NULL,
    FOREIGN KEY (fk_usuario) REFERENCES usuarios(id_usuario),
    fk_categoria INT NOT NULL,
    FOREIGN KEY (fk_categoria) REFERENCES categorias(id_categoria)
);
