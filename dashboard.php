<?php
session_start();

if (!isset($_SESSION['professor_id'])) {
    header('Location: index.html');
    exit;
}

require_once("config/database.php");

$professor_id = $_SESSION['professor_id'];
$stmt = $conn->prepare("SELECT nome FROM professor WHERE id = ?");
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$professor = $stmt->get_result()->fetch_assoc();
$primeiro_nome = explode(" ", $professor['nome'])[0];
$stmt->close();

// BUSCA EMPRÉSTIMOS
$sql = "
    SELECT e.id, a.nome AS aluno_nome, l.nome_livro AS livro_nome, 
           e.data_emprestimo, e.data_devolucao, e.status
    FROM emprestimo e
    JOIN aluno a ON e.id_aluno = a.id
    JOIN livro l ON e.id_livro = l.id
    ORDER BY e.data_emprestimo DESC
";

$result = $conn->query($sql);

// VERIFICA SE A CONSULTA FUNCIONOU
if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Biblioteca</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>

<body>
    <?php include_once("includes/header.php"); ?>

    <div class="content-dash">
        <h1 class="bem-vindo">Bem-vindo, <?php echo htmlspecialchars($primeiro_nome); ?>!</h1>
        <p>Este é o painel da biblioteca. Use o menu ao lado para navegar.</p>
    </div>

    <div class="tabela-princesa">
        <h2>Empréstimos Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Livro</th>
                    <th>Empréstimo</th>
                    <th>Devolução</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['aluno_nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['livro_nome']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_emprestimo']); ?></td>
                            <td><?php echo htmlspecialchars($row['data_devolucao']); ?></td>
                            <td><?php echo ($row['status'] == 1) ? 'Devolvido' : 'Pendente'; ?></td>
                            <td>
                                <?php if ($row['status'] == 0): ?>
                                    <a href="devolver_emprestimo.php?id=<?php echo $row['id']; ?>">Devolver</a> |
                                <?php endif; ?>
                                <a href="excluir_emprestimo.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este empréstimo?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nenhum empréstimo registrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>