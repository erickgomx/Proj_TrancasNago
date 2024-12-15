<?php
// Inicia uma sessão para tratar mensagens de erro ou sucesso
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Trancas Nagô</title>
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
    
        <!-- Seção direita: formulário de cadastro -->
        <div class="direita">
            <div class="formulario">
                <!-- Imagem do ícone de usuário -->
                <img src="../img/clara1.jpg" alt="Ícone de usuário">
                <!-- Formulário de cadastro -->
                <form action="../php/cadastro_bd.php" method="post">
                    <input type="text" name="nome" placeholder="Nome" class="form-control campo" required>
                    <input type="email" name="email" placeholder="E-mail" class="form-control campo" required>
                    <input type="password" name="senha" placeholder="Senha" class="form-control campo" required minlength="6">
                    <button type="submit" class="btn entrar">Cadastrar</button>
                    <a href="login.php" class="btn criarcon">Já tem conta? Login</a>
                </form>

                <!-- Exibe mensagens de erro ou sucesso -->
                <?php if (isset($_SESSION['mensagem'])): ?>
                    <p class="text-center mt-3" style="color: <?= $_SESSION['mensagem']['cor']; ?>;">
                        <?= $_SESSION['mensagem']['texto']; ?>
                    </p>
                    <?php unset($_SESSION['mensagem']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
