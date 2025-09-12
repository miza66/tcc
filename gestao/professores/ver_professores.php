<?php
session_start();
if (!isset($_SESSION['professor_id'])) {
    header('Location: ../../index.html');
    exit;
}

// Incluindo arquivo de conexão com o banco de dados
require_once '../../config/database.php';

// Consulta SQL para buscar os dados dos professores
$query = "SELECT id, nome, cpf, email FROM professor";
$result = $conn->query($query);

if (!$result) {
    die("Erro ao consultar professores: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../../views/img/hello_kitty_PNG22.png" type="image/x-icon">
    <title>Lista de Professores</title>
    <link rel="stylesheet" href="../../estilos/ver.css">
</head>

<body>

    <?php include_once __DIR__ . '/../../includes/header.php'; ?>

    <div class="pagina-header">
        <div class="bl-tab-container">
            <h2 class="bl-tab-title">Lista de Professores</h2>
            <table class="bl-tab-table">
                <thead class="bl-tab-head">
                    <tr class="bl-tab-head-row">
                        <th class="bl-tab-head-cell">Nome</th>
                        <th class="bl-tab-head-cell">CPF</th>
                        <th class="bl-tab-head-cell">Email</th>
                        <th class="bl-tab-head-cell">Ações</th>
                    </tr>
                </thead>
                <tbody class="bl-tab-body">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="bl-tab-body-row">
                            <td class="bl-tab-body-cell"><?= htmlspecialchars($row['nome']) ?></td>
                            <td class="bl-tab-body-cell cpf"><?= htmlspecialchars($row['cpf']) ?></td>
                            <td class="bl-tab-body-cell"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="bl-tab-body-cell">
                                <a href="excluir_professor.php?id=<?= $row['id'] ?>"
                                    onclick="return confirm('Tem certeza que deseja excluir este professor?')"
                                    class="btn-excluir">
                                    Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tdsCpf = document.querySelectorAll(".bl-tab-body-cell.cpf");
            tdsCpf.forEach(td => {
                let valor = td.textContent.replace(/\D/g, '');
                valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
                valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
                valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
                td.textContent = valor;
            });
        });
    </script>


</body>

</html>