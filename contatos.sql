CREATE DATABASE exemplocrud;

CREATE TABLE contatos (
   ID INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
   NOME VARCHAR(45) NOT NULL,
   EMAIL VARCHAR(45) NOT NULL,
   TELEFONE VARCHAR(15)
);
