<?php
// perfil.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$nomeUsuario = $_SESSION['usuario_nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Tranças Nagô</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow rounded" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Bem-vindo(a), <?= htmlspecialchars($nomeUsuario); ?>!</h2>
            <p class="text-center">Esta é sua área protegida.</p>
            <a href="../php/remover_sessao.php" class="btn btn-danger w-100">Sair</a>
        </div>
    </div>
</body>
</html>
