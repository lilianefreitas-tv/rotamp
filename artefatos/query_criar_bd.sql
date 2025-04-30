-- Criar banco de dados
CREATE DATABASE rotamp CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar o banco de dados
USE rotamp;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('solicitante', 'motorista', 'fiscal', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de veículos
CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modelo VARCHAR(100) NOT NULL,
    placa VARCHAR(20) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de motoristas
CREATE TABLE motoristas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    veiculo_id INT NOT NULL UNIQUE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de solicitações
CREATE TABLE solicitacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitante_id INT NOT NULL,
    motorista_id INT NOT NULL,
    descricao TEXT NOT NULL,
    data_ida DATE NOT NULL,
    data_volta DATE NOT NULL,
    hora_saida TIME NOT NULL,
    hora_chegada TIME NOT NULL,
    origem VARCHAR(255) NOT NULL,
    destino VARCHAR(255) NOT NULL,
    status ENUM('pendente', 'em andamento', 'finalizado') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (solicitante_id) REFERENCES usuarios(id),
    FOREIGN KEY (motorista_id) REFERENCES motoristas(id)
);

-- Tabela de percursos
CREATE TABLE percursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitacao_id INT NOT NULL,
    odometro_inicio INT,
    hora_saida_real DATETIME,
    odometro_fim INT,
    hora_chegada_real DATETIME,
    km_rodado INT,
    tempo_operacao TIME,
    assinatura_motorista BOOLEAN DEFAULT FALSE,
    assinatura_fiscal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (solicitacao_id) REFERENCES solicitacoes(id)
);

-- Tabela de comprovantes
CREATE TABLE comprovantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    percurso_id INT NOT NULL,
    disponivel BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (percurso_id) REFERENCES percursos(id)
);
