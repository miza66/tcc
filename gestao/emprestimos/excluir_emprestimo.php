<?php
require_once("../../config/database.php");

if (!isset($_GET['id'])) {
    die("ID inválido");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM emprestimo WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../../dashboard.php");
    exit;
} else {
    echo "Erro ao excluir empréstimo: " . $conn->error;
}

$stmt->close();
$conn->close();
?>