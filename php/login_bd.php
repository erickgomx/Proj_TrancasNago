<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include 'bases/head.php'; ?>
</head>
<body>
    <?php include 'bases/menu.php'; ?>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <!-- Card centralizado -->
        <div class="card p-4 shadow rounded text-center" style="max-width: 400px; width: 100%;">
            <!-- Seção de logotipo -->
            <div>
                <img src="src/img/borbo.png" class="logo mb-3" alt="Logotipo" style="max-width: 100px;">
                <h1 class="h4">VITÓRIA CHAVES</h1>
                <p class="text-muted">TRANÇAS NAGÔ</p>
            </div>
            <!-- Formulário -->
            <div class="formulario mt-4">
                <img src="src/img/perfil.webp" alt="Ícone de usuário" class="rounded-circle mb-3" style="width: 90px; height: 90px; border: 2px solid #f8c0c8;">
                <form action="php/login_bd.php" method="post">
                    <input type="email" name="email" placeholder="E-mail" class="form-control campo mb-3" required>
                    <input type="password" name="senha" placeholder="Senha" class="form-control campo mb-3" required>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Entrar</button>
                    <button type="button" class="btn btn-outline-primary w-100" onclick="window.location.href='cadastro.php'">Criar conta</button>
                </form>
                <p class="mt-3">Não tem conta? <a href="cadastro.php" class="text-primary">Cadastre-se aqui</a>.</p>
            </div>
        </div>
    </div>
    <?php include 'bases/rodape.php'; ?>
</body>
</html>
