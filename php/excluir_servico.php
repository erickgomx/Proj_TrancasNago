<?php
// Conexão com o banco de dados
require_once '../banco/conexao.php';

// Verifica se o ID foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepara a query para deletar o serviço com base no ID
        $stmt = $pdo->prepare("DELETE FROM servicos WHERE id = :id");
        $stmt->execute([':id' => $id]);

        // Verifica se o serviço foi deletado
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Serviço excluído com sucesso!'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Erro: Serviço não encontrado.'); window.location.href = 'index.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Erro ao excluir o serviço: " . $e->getMessage();
    }
} else {
    echo "ID do serviço não informado.";
}
?>
