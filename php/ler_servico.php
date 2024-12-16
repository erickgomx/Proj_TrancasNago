<?php
// Conexão com o banco de dados
require_once '../banco/conexao.php';

// Verifica se o ID foi enviado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Busca os dados do serviço no banco de dados usando PDO
        $stmt = $pdo->prepare("SELECT * FROM servicos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $servico = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o serviço foi encontrado
        if (!$servico) {
            die("Serviço não encontrado.");
        }
    } catch (PDOException $e) {
        die("Erro ao buscar o serviço: " . $e->getMessage());
    }
} else {
    die("ID inválido.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Serviço</title>
    <!-- Importação do CSS externo -->
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <div class="crud-container">
        <h2>Detalhes do Serviço</h2>

        <!-- Exibição dos dados do serviço -->
        <div class="service-details">
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($servico['nome']); ?></p>
            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($servico['descricao']); ?></p>
            <p><strong>Preço:</strong> R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></p>
            <p>
                <strong>Imagem:</strong><br>
                <img src="../img/<?php echo htmlspecialchars($servico['imagem']); ?>" alt="Imagem do serviço" style="max-width: 300px; border-radius: 8px;">
            </p>
        </div>

        <!-- Botão para voltar à lista de serviços -->
        <a href="index.php" class="save-btn">Voltar</a>
    </div>
</body>
</html>
