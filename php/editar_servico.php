<?php
// Conexão com o banco de dados
require_once '../banco/conexao.php';

// Verifica se o ID foi enviado via GET (para carregar os dados)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Busca os dados do serviço no banco de dados
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
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Processa a atualização do serviço
    $id = $_POST['id']; // Obtém o ID do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];

    // Validação básica
    if (empty($nome) || empty($descricao) || empty($preco) || !is_numeric($preco)) {
        die("Erro: Todos os campos devem ser preenchidos corretamente.");
    }

    try {
        // Atualiza o serviço no banco de dados
        $stmt = $pdo->prepare("UPDATE servicos SET nome = :nome, descricao = :descricao, preco = :preco WHERE id = :id");
        $stmt->execute([
            ':nome' => $nome,
            ':descricao' => $descricao,
            ':preco' => $preco,
            ':id' => $id,
        ]);

        echo "<script>alert('Serviço atualizado com sucesso!'); window.location.href = 'dashboard.php';</script>";
    } catch (PDOException $e) {
        die("Erro ao atualizar o serviço: " . $e->getMessage());
    }
} else {
    die("ID inválido.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Serviço</title>
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <div class="crud-container">
        <h2>Editar Serviço</h2>
        <form method="POST" action="editar_servico.php">
            <!-- Campo oculto para o ID do serviço -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($servico['id']); ?>">

            <!-- Campo para o Nome -->
            <div class="form-group campo">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($servico['nome']); ?>" required>
            </div>

            <!-- Campo para a Descrição -->
            <div class="form-group campo">
                <label for="descricao">Descrição:</label>
                <input type="text" name="descricao" id="descricao" value="<?php echo htmlspecialchars($servico['descricao']); ?>" required>
            </div>

            <!-- Campo para o Preço -->
            <div class="form-group campo">
                <label for="preco">Preço:</label>
                <input type="number" name="preco" id="preco" step="0.01" value="<?php echo htmlspecialchars($servico['preco']); ?>" required>
            </div>

            <!-- Botão para salvar alterações -->
            <button type="submit" class="save-btn">Salvar</button>
        </form>
    </div>
</body>
</html>
