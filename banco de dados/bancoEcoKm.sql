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

CREATE TABLE plano(
id_plano INT PRIMARY KEY AUTO_INCREMENT,
tipo VARCHAR(15),
preco INT,
cpf VARCHAR(15),
CONSTRAINT fk_cpf_plano FOREIGN KEY (cpf) REFERENCES usuario(cpf) ON DELETE CASCADE
);

CREATE TABLE viagem(
	id_viagem INT PRIMARY KEY AUTO_INCREMENT,
    tipoMotor VARCHAR(5),
    tipoCombustivel VARCHAR(8),
    distancia FLOAT,
    precoCombustivel FLOAT,
    partida VARCHAR(255),
    chegada VARCHAR(255),
    tempoEstimado VARCHAR(50),
    consumo FLOAT,
    custo FLOAT,
    cpf VARCHAR (15),
    CONSTRAINT fk_cpf_viagens FOREIGN KEY (cpf) REFERENCES usuario(cpf) ON DELETE CASCADE
);

CREATE TABLE logUsers(
	idLog INT PRIMARY KEY AUTO_INCREMENT,
    usuario VARCHAR(50) NOT NULL,
    acao TEXT NOT NULL,
    dataLog DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- código para inserir o admin
INSERT INTO usuario VALUES ('admin', 'admin@admin.com', '12345678909', '5521999999999','5521999999999', '2024-10-22', '-----', 'admin', '-----', 'admin', '-----', 'admin1234', '-----', 'Outro' );