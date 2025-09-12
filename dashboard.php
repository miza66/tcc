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

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

$hoje = date('Y-m-d'); // Data de hoje para status
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="views/img/hello_kitty_PNG22.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Biblioteca</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>

<body>
    <?php include_once("includes/header.php"); ?>

    <div class="my-header body">
        <div class="content-dash">
            <h1 class="bem-vindo">Bem-vindo, <?php echo htmlspecialchars($primeiro_nome); ?>!</h1>
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
                            <?php
                            // Define o status exibido baseado na data e no status real
                            if ($row['status'] == 2) {
                                $status_texto = "Devolvido";
                            } else {
                                $status_texto = ($hoje > $row['data_devolucao']) ? "Pendente" : "Em andamento";
                            }
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['aluno_nome']); ?></td>
                                <td><?php echo htmlspecialchars($row['livro_nome']); ?></td>
                                <td><?php echo htmlspecialchars($row['data_emprestimo']); ?></td>
                                <td><?php echo htmlspecialchars($row['data_devolucao']); ?></td>
                                <td><?php echo $status_texto; ?></td>
                                <td>
                                    <?php if ($row['status'] != 2): ?>
                                        <a href="javascript:void(0);" style="color: blue; text-decoration: none;" onclick="devolverEmprestimo(<?php echo $row['id']; ?>, this)">Devolver</a> |
                                    <?php else: ?>
                                        <a href="javascript:void(0);" style="color: blue; text-decoration: none;" onclick="cancelarDevolucao(<?php echo $row['id']; ?>, this)">Cancelar</a> |
                                    <?php endif; ?>
                                    <a href="gestao/emprestimos/excluir_emprestimo.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este empréstimo?');">Excluir</a>
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
    </div>

    <script>
        function devolverEmprestimo(id, botao) {
            fetch("gestao/emprestimos/devolver_emprestimo.php?id=" + id)
                .then(res => res.text())
                .then(res => {
                    if (res === "ok") {
                        botao.innerText = "Cancelar";
                        botao.setAttribute("onclick", `cancelarDevolucao(${id}, this)`);
                        botao.closest("tr").querySelector("td:nth-child(5)").innerText = "Devolvido";
                    } else {
                        alert("Erro ao devolver!");
                    }
                });
        }

        function cancelarDevolucao(id, botao) {
            fetch("gestao/emprestimos/cancelar_devolucao.php?id=" + id)
                .then(res => res.text())
                .then(res => {
                    if (res === "ok") {
                        let dataDevolucao = botao.closest("tr").querySelector("td:nth-child(4)").innerText;
                        let hoje = new Date().toISOString().split("T")[0];

                        let status = (hoje > dataDevolucao) ? "Pendente" : "Em andamento";
                        botao.closest("tr").querySelector("td:nth-child(5)").innerText = status;

                        botao.innerText = "Devolver";
                        botao.setAttribute("onclick", `devolverEmprestimo(${id}, this)`);
                    } else {
                        alert("Erro ao cancelar!");
                    }
                });
        }
    </script>

</body>

</html>