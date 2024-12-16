<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../banco/conexao.php';

/*
 * Este script realiza as seguintes operações no banco de dados:
 * - READ: Listar os serviços existentes
 * - CREATE: Adicionar um novo serviço (nome, imagem, descrição e preço)
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
            echo "<script>alert('Serviço adicionado com sucesso!'); window.location.href = 'dashboard.php';</script>";
        } catch (PDOException $e) {
            die("Erro ao adicionar serviço: " . $e->getMessage());
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
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .crud-header {
            background-color: #d63384;
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .crud-header h1 {
            margin: 0;
            font-size: 2.5rem;
        }
        .crud-container {
            width: 90%;
            max-width: 1200px;
            margin: 2rem auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }
        .add-service-form {
            background-color: #fdf2f8;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .add-service-form h2 {
            color: #d63384;
            margin-bottom: 1rem;
            text-align: center;
        }
        .add-service-form .form-group {
            margin-bottom: 1rem;
        }
        .add-service-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .add-service-form input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .add-service-form button {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .add-service-form button:hover {
            background-color: #b2226c;
        }
        .service-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .service-item {
            background-color: #fdf2f8;
            padding: 1rem;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .service-item img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }
        .service-item span {
            font-weight: bold;
            font-size: 1rem;
            color: #d63384;
        }
        .actions {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
        .actions a {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            text-decoration: none;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .delete-btn {
            background-color: #ff6b6b;
        }
        .delete-btn:hover {
            background-color: #e63946;
        }
        .edit-btn {
            background-color: #4caf50;
        }
        .edit-btn:hover {
            background-color: #388e3c;
        }
        .read-btn {
            background-color: #2196f3;
        }
        .read-btn:hover {
            background-color: #1976d2;
        }
        .crud-footer {
            text-align: center;
            padding: 1rem;
            background-color: #d63384;
            color: white;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <header class="crud-header">
        <h1>Gerenciamento de Serviços</h1>
    </header>

    <div class="crud-container">
        <section class="add-service-form">
            <h2>Adicionar Novo Serviço</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" required>
                </div>
                <div class="form-group">
                    <label for="preco">Preço:</label>
                    <input type="number" step="0.01" id="preco" name="preco" required>
                </div>
                <div class="form-group">
                    <label for="imagem">Imagem:</label>
                    <input type="file" id="imagem" name="image" required>
                </div>
                <button type="submit">Adicionar Serviço</button>
            </form>
        </section>

        <section class="service-list">
            <h2>Serviços Existentes</h2>
            <?php foreach ($servicos as $servico): ?>
                <div class="service-item">
                    <img src="../img/<?php echo htmlspecialchars($servico['imagem']); ?>" alt="<?php echo htmlspecialchars($servico['nome']); ?>">
                    <span><?php echo htmlspecialchars($servico['nome']); ?></span>
                    <div class="actions">
                        <a href="excluir_servico.php?id=<?php echo $servico['id']; ?>" class="delete-btn">Remover</a>
                        <a href="editar_servico.php?id=<?php echo $servico['id']; ?>" class="edit-btn">Editar</a>
                        <a href="ler_servico.php?id=<?php echo $servico['id']; ?>" class="read-btn">Ler</a>
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
