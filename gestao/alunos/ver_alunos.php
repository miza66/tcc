<?php
// Incluindo arquivo de conexão com o banco de dados
require_once '../../config/database.php';

// Consulta SQL para buscar os dados dos alunos, incluindo o ID
$query = "SELECT id, nome, serie, email FROM aluno";
$result = $conn->query($query);

if (!$result) {
    die("Erro ao consultar alunos: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Lista de Alunos</title>
    <link rel="stylesheet" href="../../estilos/ver.css">
</head>

<body>
    <?php include_once __DIR__ . '/../../includes/header.php'; ?>

    <div class="pagina-header">
        <div class="bl-tab-container">
            <h2 class="bl-tab-title">Lista de Alunos</h2>
            <table class="bl-tab-table">
                <thead class="bl-tab-head">
                    <tr class="bl-tab-head-row">
                        <th class="bl-tab-head-cell">Nome</th>
                        <th class="bl-tab-head-cell">Série</th>
                        <th class="bl-tab-head-cell">Email</th>
                        <th class="bl-tab-head-cell">Ações</th>
                    </tr>
                </thead>
                <tbody class="bl-tab-body">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="bl-tab-body-row">
                            <td class="bl-tab-body-cell"><?= htmlspecialchars($row['nome']) ?></td>
                            <td class="bl-tab-body-cell"><?= htmlspecialchars($row['serie']) ?></td>
                            <td class="bl-tab-body-cell"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="bl-tab-body-cell">
                                <a href="excluir_aluno.php?id=<?= $row['id'] ?>"
                                    onclick="return confirm('Tem certeza que deseja excluir este aluno?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>