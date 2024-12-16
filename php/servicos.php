<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../banco/conexao.php';

/*
 * Este script realiza as seguintes operações no banco de dados:
 * - READ: Listar os serviços existentes
 * - CREATE: Adicionar um novo serviço (nome, imagem, descrição e preço)
 */

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // RECEBENDO OS DADOS DO FORMULÁRIO PARA O CREATE

    // Recebe os campos do formulário
    $nome = trim($_POST['nome']);  // Nome da trança
    $descricao = trim($_POST['descricao']); // Descrição
    $preco = trim($_POST['preco']); // Preço

    // Valida se todos os campos obrigatórios estão preenchidos
    if (empty($nome) || empty($descricao) || empty($preco) || !is_numeric($preco)) {
        die("Erro: Todos os campos devem ser preenchidos corretamente.");
    }

    // Upload da imagem
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imagem_nome = basename($_FILES['image']['name']); // Obtém o nome do arquivo
        $imagem_caminho = "../img/" . $imagem_nome; // Define o caminho para salvar

        // Move a imagem para a pasta /img
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagem_caminho)) {
            die("Erro ao enviar a imagem.");
        }
    } else {
        die("Erro: A imagem é obrigatória.");
    }

    // Prepara a query SQL para inserir os dados no banco com PDO
    try {
        $sql = "INSERT INTO servicos (nome, imagem, descricao, preco) VALUES (:nome, :imagem, :descricao, :preco)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':imagem' => $imagem_nome,
            ':descricao' => $descricao,
            ':preco' => $preco
        ]);

        echo "<script>alert('Serviço adicionado com sucesso!'); window.location.href = 'index.php';</script>";
    } catch (PDOException $e) {
        die("Erro ao adicionar serviço: " . $e->getMessage());
    }
}

// LISTAGEM DOS SERVIÇOS EXISTENTES (READ)
try {
    $sql = "SELECT * FROM servicos";
    $stmt = $pdo->query($sql);
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
    <title>Serviços - TrancasNagô</title>
    <link rel="stylesheet" href="../src/styles.css"> <!-- CSS Externo -->
</head>
<body>
    <header class="crud-header">
        <h1>Gerenciamento de Serviços</h1>
    </header>

    <div class="crud-container">
        <!-- FORMULÁRIO PARA ADICIONAR SERVIÇOS -->
        <section class="crud-form">
            <h2>Adicionar Serviço</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome da Trança:</label>
                    <input type="text" id="nome" name="nome" placeholder="Nome da trança" required>
                </div>
                <div class="form-group">
                    <label for="image">Imagem:</label>
                    <input type="file" id="image" name="image" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Descrição" required>
                </div>
                <div class="form-group">
                    <label for="preco">Preço:</label>
                    <input type="number" id="preco" name="preco" placeholder="Preço" step="0.01" required>
                </div>
                <button type="submit" class="save-btn">Salvar</button>
            </form>
        </section>

        <!-- LISTA DE SERVIÇOS EXISTENTES -->
        <section class="service-list">
            <h2>Serviços Cadastrados</h2>
            <?php if (!empty($servicos)): ?>
                <?php foreach ($servicos as $servico): ?>
                    <div class="service-item">
                        <img src="../img/<?= htmlspecialchars($servico['imagem']) ?>" alt="<?= htmlspecialchars($servico['nome']) ?>">
                        <span><strong>Nome:</strong> <?= htmlspecialchars($servico['nome']) ?></span>
                        <span><strong>Descrição:</strong> <?= htmlspecialchars($servico['descricao']) ?></span>
                        <span><strong>Preço:</strong> R$ <?= number_format($servico['preco'], 2, ',', '.') ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum serviço cadastrado.</p>
            <?php endif; ?>
        </section>
    </div>

    <footer class="crud-footer">
        <p>&copy; 2024 TrancasNagô. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
