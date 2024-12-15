<?php
// Inicia uma sessão para exibir mensagens
session_start();

// Inclui a conexão com o banco de dados
include '../banco/conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Verifica se os campos estão preenchidos
    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['mensagem'] = [
            'texto' => 'Todos os campos são obrigatórios.',
            'cor' => 'red'
        ];
        header('Location: cadastro.php');
        exit;
    }

    try {
        // Verifica se o e-mail já está cadastrado
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['mensagem'] = [
                'texto' => 'O e-mail já está cadastrado.',
                'cor' => 'red'
            ];
            header('Location: cadastro.php');
            exit;
        }

        // Insere o novo usuário no banco
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT) // Criptografa a senha
        ]);

        // Sucesso no cadastro
        $_SESSION['mensagem'] = [
            'texto' => 'Cadastro realizado com sucesso! Faça login.',
            'cor' => 'green'
        ];
        header('Location: ../php/login.php');
        exit;
    } catch (PDOException $e) {
        // Erro no banco de dados
        $_SESSION['mensagem'] = [
            'texto' => 'Erro ao cadastrar: ' . $e->getMessage(),
            'cor' => 'red'
        ];
        header('Location: cadastro.php');
        exit;
    }
} else {
    // Redireciona se o acesso não for via POST
    $_SESSION['mensagem'] = [
        'texto' => 'Acesso inválido.',
        'cor' => 'red'
    ];
    header('Location: cadastro.php');
    exit;
}
?>
