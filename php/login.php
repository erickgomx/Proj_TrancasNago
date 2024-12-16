<?php
// login.php

// Inclui a conexão com o banco de dados
include '../banco/conexao.php';

// Inicia a sessão para gerenciar o estado do usuário logado
session_start();

// Verifica se o usuário já está logado
if (isset($_SESSION['usuario_id'])) {
    // Redireciona para a página de perfil caso o usuário já esteja logado
    header('Location: perfil.php');
    exit;
}

// Verifica se o método de requisição é POST (submissão do formulário)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); // Limpa o e-mail enviado pelo formulário
    $senha = trim($_POST['senha']); // Limpa a senha enviada pelo formulário

    // Validação do e-mail no back-end para garantir formato válido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } else {
        try {
            // Consulta ao banco de dados para verificar se o e-mail existe
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch(); // Obtém o registro do banco de dados

            // Verificação da senha usando password_verify
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                session_regenerate_id(); // Regenera o ID da sessão por segurança
                $_SESSION['usuario_id'] = $usuario['id']; // Armazena o ID do usuário na sessão
                $_SESSION['usuario_nome'] = $usuario['nome']; // Armazena o nome do usuário na sessão
                header('Location: index.php'); // Redireciona para a página de perfil
                exit;
            } else {
                $erro = "E-mail ou senha inválidos."; // Mensagem de erro genérica
            }
        } catch (PDOException $e) {
            $erro = "Erro ao efetuar login: " . $e->getMessage(); // Captura erros do banco
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitória Chaves - Tranças Nagô</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS Externo -->
    <link rel="stylesheet" href="../src/styles.css">
</head>
<body>
    <div class="container">
        <!-- Seção esquerda: logotipo e descrição -->
        <div class="esquerda">
            <img src="../img/borbo.png" class="logo">
            <h1>VITÓRIA CHAVES</h1>
            <p>TRANÇAS NAGÔ</p>
        </div>
    
        <!-- Seção direita: formulário de login -->
        <div class="direita">
            <div class="formulario">
                <!-- Imagem do ícone de usuário -->
                <img src="../img/clara1.jpg" alt="Ícone de usuário">
                <!-- Formulário de login -->
                <form method="post" action="">
                    <input type="email" name="email" placeholder="E-mail" class="form-control campo" required>
                    <input type="password" name="senha" placeholder="Senha" class="form-control campo" required>
                    <button type="submit" class="btn entrar">Entrar</button>
                    <a href="cadastro.php" class="btn criarcon">Criar conta</a>
                </form>
                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger mt-3"> <?= $erro ?> </div>
                <?php endif; ?>
                <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso'): ?>
                    <div class="alert alert-success mt-3">Cadastro realizado com sucesso! Faça login.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
