<?php
// Incluindo arquivo de conexão com o banco de dados
require_once '../../config/database.php';

// Verifica se o ID foi passado via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Prepara e executa a exclusão
    $stmt = $conn->prepare("DELETE FROM aluno WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redireciona de volta para a lista de alunos
        header("Location: ver_alunos.php");
        exit;
    } else {
        echo "Erro ao excluir aluno: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "ID inválido.";
}

$conn->close();
?>