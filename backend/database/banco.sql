CREATE DATABASE IF NOT EXISTS recrutamento CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE recrutamento;

CREATE TABLE candidatos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  token VARCHAR(255) DEFAULT NULL
);

CREATE TABLE vagas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  descricao TEXT,
  tipo ENUM('CLT','PJ','Freelancer') NOT NULL,
  status ENUM('active','paused','closed') NOT NULL DEFAULT 'active',
  criado_por INT NULL,
  CONSTRAINT fk_vaga_criador
    FOREIGN KEY (criado_por) REFERENCES candidatos(id)
    ON DELETE SET NULL
);

CREATE TABLE inscricoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vaga_id INT NOT NULL,
  candidato_id INT NOT NULL,
  UNIQUE KEY uq_vaga_candidato (vaga_id, candidato_id),
  FOREIGN KEY (vaga_id) REFERENCES vagas(id) ON DELETE CASCADE,
  FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE
);
