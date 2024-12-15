<?php
// Configuração de conexão com o banco de dados
$host = 'localhost'; // Host do servidor
$dbname = 'trancas_nago'; // Nome do banco de dados
$username = 'root'; // Usuário do banco (alterar se necessário)
$password = ''; // Senha do banco (alterar se necessário)

try {
    // Criando a conexão com o banco usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilita os erros
} catch (PDOException $e) {
    // Exibe a mensagem de erro e interrompe a execução
    die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
}
?>
