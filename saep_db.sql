-- ============================================================
-- SAEP — Script de criação e população do banco de dados
-- Sistema: AutoOficina Pro
-- Estudante: Carlos André
-- Mínimo de 3 registros por tabela
-- ============================================================

CREATE DATABASE IF NOT EXISTS autooficina
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE autooficina;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS agendamentos;
DROP TABLE IF EXISTS veiculos;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS usuarios;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  perfil VARCHAR(30) NOT NULL DEFAULT 'Operador'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO usuarios (nome, email, senha, perfil) VALUES
('Carlos André Gestor', 'gestor@autooficina.local', '$2y$10$vhQGZT3QzOfGwNJNqK0F5OZrd/cwwR0f//0MmpfkTpxDTRtK7Uc4K', 'Administrador'),
('Fernanda Recepção', 'recepcao@autooficina.local', '$2y$10$vhQGZT3QzOfGwNJNqK0F5OZrd/cwwR0f//0MmpfkTpxDTRtK7Uc4K', 'Operador'),
('Roberto Técnico', 'oficina@autooficina.local', '$2y$10$vhQGZT3QzOfGwNJNqK0F5OZrd/cwwR0f//0MmpfkTpxDTRtK7Uc4K', 'Técnico');

CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cpf VARCHAR(255) NOT NULL COMMENT 'CPF criptografado AES-256-CBC',
  telefone VARCHAR(20) NULL,
  email VARCHAR(120) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO clientes (nome, cpf, telefone, email) VALUES
('Pedro Henrique Lima', 'h0YVixdee9AtMQWJ3V9fIQ==', '(81) 99111-2233', 'pedro.lima@email.com'),
('Juliana Costa Mendes', 'Lz5Z6Rh8NqRRPuv1i5fY8Q==', '(81) 98222-3344', 'juliana.mendes@email.com'),
('Ricardo Alves Pinto', '39rq161Xep2icK3r7BuTGQ==', '(81) 97333-4455', 'ricardo.pinto@email.com');

CREATE TABLE veiculos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  placa VARCHAR(10) NOT NULL,
  marca VARCHAR(50) NOT NULL,
  modelo VARCHAR(50) NOT NULL,
  ano INT NULL,
  CONSTRAINT fk_veiculos_cliente
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO veiculos (cliente_id, placa, marca, modelo, ano) VALUES
(1, 'PQR2A45', 'Honda', 'Civic', 2018),
(2, 'STU8B12', 'Hyundai', 'HB20', 2021),
(2, 'VWX3C67', 'Renault', 'Kwid', 2020),
(3, 'YZA5D90', 'Ford', 'Ka', 2017);

CREATE TABLE agendamentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  veiculo_id INT NOT NULL,
  data_agendamento DATETIME NOT NULL,
  servico VARCHAR(150) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Agendado',
  observacoes TEXT NULL,
  CONSTRAINT fk_agendamentos_cliente
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_agendamentos_veiculo
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO agendamentos (cliente_id, veiculo_id, data_agendamento, servico, status, observacoes) VALUES
(1, 1, '2026-12-02 08:30:00', 'Troca de pastilhas de freio', 'Agendado', 'Verificar disco dianteiro'),
(2, 2, '2026-12-03 11:00:00', 'Revisão completa 30.000 km', 'Agendado', NULL),
(3, 4, '2026-12-04 15:45:00', 'Troca de correia dentada', 'Em andamento', 'Kit completo solicitado'),
(2, 3, '2026-12-05 09:20:00', 'Geometria e balanceamento', 'Agendado', 'Cliente retira no mesmo dia');

SELECT 'Banco autooficina criado e populado.' AS mensagem;
SELECT 'usuarios' AS tabela, COUNT(*) AS qtd FROM usuarios
UNION ALL SELECT 'clientes', COUNT(*) FROM clientes
UNION ALL SELECT 'veiculos', COUNT(*) FROM veiculos
UNION ALL SELECT 'agendamentos', COUNT(*) FROM agendamentos;
