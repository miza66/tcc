<?php
require_once '../../config/database.php';

// Verifica se o ID foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do professor não informado.");
}

$id = intval($_GET['id']); // Segurança: garante que é um número inteiro

// Prepara a query de exclusão
$query = "DELETE FROM professor WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Redireciona de volta para a lista de professores
    header("Location: ver_professores.php");
    exit;
} else {
    echo "Erro ao excluir professor: " . $conn->error;
}

$stmt->close();
$conn->close();
?>