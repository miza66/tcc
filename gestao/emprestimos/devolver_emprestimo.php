<?php
require_once("../../config/database.php");

if (!isset($_GET['id'])) {
    echo "erro";
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("UPDATE emprestimo SET status = 2 WHERE id = ?");
$stmt->bind_param("i", $id);

// Executa e verifica erro crítico
if ($stmt->execute() === false) {
    echo "erro";
} else {
    echo "ok"; // retorna para o JS
}

$stmt->close();
$conn->close();
?>