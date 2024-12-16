<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../banco/conexao.php';

/*
 * Este script realiza as seguintes operações no banco de dados:
 * - READ: Listar os serviços existentes
 * - CREATE: Adicionar um novo serviço (nome, imagem, descrição e preço)
 * - UPDATE: Editar um serviço existente
 * - DELETE: Remover um serviço
 */

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    // CREATE: Adicionar um novo serviço
    if ($action === 'create') {
        $nome = trim($_POST['nome']);
        $descricao = trim($_POST['descricao']);
        $preco = trim($_POST['preco']);

        if (empty($nome) || empty($descricao) || empty($preco) || !is_numeric($preco)) {
            die("Erro: Todos os campos devem ser preenchidos corretamente.");
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $imagem_nome = basename($_FILES['image']['name']);
            $imagem_caminho = "../img/" . $imagem_nome;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagem_caminho)) {
                die("Erro ao enviar a imagem.");
            }
        } else {
            die("Erro: A imagem é obrigatória.");
        }

        try {
            $sql = "INSERT INTO servicos (nome, imagem, descricao, preco) VALUES (:nome, :imagem, :descricao, :preco)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => $nome, ':imagem' => $imagem_nome, ':descricao' => $descricao, ':preco' => $preco]);
            echo "<script>alert('Serviço adicionado com sucesso!'); window.location.href = 'index.php';</script>";
        } catch (PDOException $e) {
            die("Erro ao adicionar serviço: " . $e->getMessage());
        }
    }

    // DELETE: Remover um serviço
    if ($action === 'delete') {
        $id = $_POST['id'];
        try {
            $sql = "DELETE FROM servicos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            echo "<script>alert('Serviço removido com sucesso!'); window.location.href = 'index.php';</script>";
        } catch (PDOException $e) {
            die("Erro ao remover serviço: " . $e->getMessage());
        }
    }

    // UPDATE: Editar um serviço
    if ($action === 'update') {
        $id = $_POST['id'];
        $nome = trim($_POST['nome']);
        $descricao = trim($_POST['descricao']);
        $preco = trim($_POST['preco']);

        if (empty($nome) || empty($descricao) || empty($preco) || !is_numeric($preco)) {
            die("Erro: Todos os campos devem ser preenchidos corretamente.");
        }

        try {
            $sql = "UPDATE servicos SET nome = :nome, descricao = :descricao, preco = :preco WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => $nome, ':descricao' => $descricao, ':preco' => $preco, ':id' => $id]);
            echo "<script>alert('Serviço atualizado com sucesso!'); window.location.href = 'index.php';</script>";
        } catch (PDOException $e) {
            die("Erro ao atualizar serviço: " . $e->getMessage());
        }
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
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <header class="crud-header">
        <h1>Gerenciamento de Serviços</h1>
    </header>

    <div class="crud-container">
        <section class="service-list">
            <h2>Serviços Existentes</h2>
            <?php foreach ($servicos as $servico): ?>
                <div class="service-item">
                    <img src="../img/<?php echo htmlspecialchars($servico['imagem']); ?>" alt="<?php echo htmlspecialchars($servico['nome']); ?>">
                    <span><?php echo htmlspecialchars($servico['nome']); ?></span>
                    <div class="actions">
                        <form method="POST" action="" style="display: inline-block;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $servico['id']; ?>">
                            <button type="submit" class="delete-btn">Remover</button>
                        </form>
                        <form method="GET" action="editar.php" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?php echo $servico['id']; ?>">
                            <button type="submit" class="edit-btn">Editar</button>
                        </form>
                        <form method="GET" action="detalhes.php" style="display: inline-block;">
                            <input type="hidden" name="id" value="<?php echo $servico['id']; ?>">
                            <button type="submit" class="read-btn">Ler</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </div>

    <footer class="crud-footer">
        <p>&copy; 2024 TrancasNagô. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
