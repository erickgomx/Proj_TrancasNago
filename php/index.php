<?php
require_once '../banco/conexao.php'; // Inclui o arquivo de conexão com PDO

// Consulta SQL para listar os serviços
try {
    $sql = "SELECT * FROM servicos";
    $stmt = $pdo->query($sql);

    // Verifica se há resultados
    $servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar serviços: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD - TrancasNagô</title>
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <header class="crud-header">
        <h1>Lista de serviços</h1>
        <a href="../php/remover_sessao.php" class="logout-btn">Sair</a>
    </header>

    <div class="crud-container">
        <section class="service-list">
            <?php
            if (count($servicos) > 0) {
                foreach ($servicos as $servico) {
                    echo '<div class="service-item">';
                    echo '<img src="../img/' . htmlspecialchars($servico['imagem']) . '" alt="' . htmlspecialchars($servico['nome']) . '">';
                    echo '<span><strong>Nome:</strong> ' . htmlspecialchars($servico['nome']) . '</span>';
                    echo '<span><strong>Descrição:</strong> ' . htmlspecialchars($servico['descricao']) . '</span>';
                    echo '<span><strong>Preço:</strong> R$ ' . number_format($servico['preco'], 2, ',', '.') . '</span>';
                    echo '<a href="editar_servico.php?id=' . $servico['id'] . '" class="edit-btn">Editar</a>';
                    echo '<a href="ler_servico.php?id=' . $servico['id'] . '" class="read-btn">Ler</a>';
                    echo '<a href="excluir_servico.php?id=' . $servico['id'] . '" class="delete-btn" onclick="return confirm(\'Tem certeza que deseja excluir este serviço?\')">Excluir</a>';
                    echo '</div>';
                }
            } else {
                echo "<p class='no-service-msg'>Nenhum serviço cadastrado.</p>";
            }
            ?>
        </section>

        <div class="crud-buttons">
            <a href="servicos.php" class="add-service-btn">Adicionar Serviço</a>
        </div>
    </div>

    <footer class="crud-footer">
        <p>&copy; 2024 TrancasNagô. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
