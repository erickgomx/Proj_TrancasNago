-- Tabela de serviços
CREATE TABLE IF NOT EXISTS servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255) NOT NULL
);

-- Inserir serviços
INSERT INTO servicos (nome, preco, imagem) VALUES
('Trança Frontal - 1', 30.00, 'tranca1.jpg'),
('Trança Frontal - 2', 25.00, 'tranca2.jpg'),
('Trança Lateral - 1', 30.00, 'tranca3.jpg'),
('Trança Lateral - 2', 25.00, 'tranca4.jpg');

-- Tabela de horários
CREATE TABLE IF NOT EXISTS horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dia DATE NOT NULL,
    hora TIME NOT NULL,
    status ENUM('Disponível', 'Lotado') DEFAULT 'Disponível',
    UNIQUE KEY (dia, hora)
);

-- Inserir horários
INSERT INTO horarios (dia, hora, status) VALUES
('2024-06-01', '15:00:00', 'Disponível'),
('2024-06-01', '19:00:00', 'Lotado'),
('2024-06-02', '15:00:00', 'Disponível'),
('2024-06-02', '19:00:00', 'Lotado'),
('2024-06-03', '15:00:00', 'Disponível'),
('2024-06-03', '19:00:00', 'Disponível');

-- Adicionar índices
ALTER TABLE horarios ADD INDEX idx_dia_hora (dia, hora);
