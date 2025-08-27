<?php
require_once("../../config/database.php");

if (!isset($_GET['id'])) {
    echo "erro";
    exit;
}

$id = intval($_GET['id']);

// Pega a data de devolução
$stmt = $conn->prepare("SELECT data_devolucao FROM emprestimo WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dados = $result->fetch_assoc();
$stmt->close();

if (!$dados) {
    echo "erro";
    exit;
}

$data_devolucao = $dados['data_devolucao'];
$hoje = date('Y-m-d');

// Define status baseado na data
$status = ($hoje > $data_devolucao) ? 1 : 0;

$stmt = $conn->prepare("UPDATE emprestimo SET status = ? WHERE id = ?");
$stmt->bind_param("ii", $status, $id);

if ($stmt->execute() === false) {
    echo "erro";
} else {
    echo "ok"; // retorna para o JS
}

$stmt->close();
$conn->close();
?>