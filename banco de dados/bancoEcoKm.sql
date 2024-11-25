CREATE DATABASE ecokm;
USE ecokm;

CREATE TABLE usuario(
	nome VARCHAR(50) NOT NULL,
	email VARCHAR(40) NOT NULL,
    cpf VARCHAR(15) PRIMARY KEY,
    celular1 VARCHAR(18) NOT NULL,
    celular2 VARCHAR(18) NOT NULL,
    dt_nasc DATE NOT NULL,
    cep VARCHAR(9) NOT NULL,
    nomeMae VARCHAR(50) NOT NULL,
    endereco VARCHAR(40) NOT NULL,
    login VARCHAR(6) NOT NULL,
    cidade VARCHAR(30) NOT NULL,
    senha VARCHAR(60) NOT NULL,
    bairro VARCHAR(30) NOT NULL,
    sexo VARCHAR(10) NOT NULL
);

CREATE TABLE carro(
	id_carro INT PRIMARY KEY AUTO_INCREMENT,
    modelo VARCHAR(30),
    marca VARCHAR(20),
    motor VARCHAR(3),
    cpf VARCHAR(15),
    CONSTRAINT fk_cpf_carro FOREIGN KEY (cpf) REFERENCES usuario(cpf) ON DELETE CASCADE
);

CREATE TABLE plano(
id_plano INT PRIMARY KEY AUTO_INCREMENT,
tipo VARCHAR(15),
preco FLOAT,
cpf VARCHAR(15),
CONSTRAINT fk_cpf_plano FOREIGN KEY (cpf) REFERENCES usuario(cpf) ON DELETE CASCADE
);

-- código para inserir a conta admin
INSERT INTO usuario VALUES('admin','admin@gmail.com','12345678900','12345678900','12345678900','2024-07-15','00000000','admin','-----','admin','-----','admin1234','-----','-----');