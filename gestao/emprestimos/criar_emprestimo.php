<?php
include '../../config/database.php'; // define $conn como mysqli_connect()

$mensagem = '';

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluno = $_POST['id_aluno'];
    $id_livro = $_POST['id_livro'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'];
    $status = 0;

    $id_professor = 1; // professor fixo, pode ser alterado conforme login

    $stmt = mysqli_prepare($conn, "INSERT INTO emprestimo (id_aluno, id_professor, id_livro, data_emprestimo, data_devolucao, status) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiissi", $id_aluno, $id_professor, $id_livro, $data_emprestimo, $data_devolucao, $status);

    if (mysqli_stmt_execute($stmt)) {
        $mensagem = "Empréstimo registrado com sucesso!";
    } else {
        $mensagem = "Erro ao registrar empréstimo: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

// Buscar alunos e livros
$alunos = mysqli_query($conn, "SELECT id, nome FROM aluno");
$livros = mysqli_query($conn, "SELECT id, nome_livro FROM livro");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Criar Empréstimo</title>
    <link rel="stylesheet" href="../../estilos/cadastro.css">
</head>

<body>

    <?php include_once __DIR__ . '/../../includes/header.php'; ?>

    <div class="pagina-header">
        <div class="bl-cad-container">
            <h1 class="bl-cad-title">Criar Empréstimo</h1>

            <?php if ($mensagem): ?>
                <p class="bl-cad-message"><?= $mensagem ?></p>
            <?php endif; ?>

            <form class="bl-cad-form" method="POST">
                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">Aluno:</label>
                    <select class="bl-cad-input" name="id_aluno" required>
                        <option value="">Selecione o aluno</option>
                        <?php while ($aluno = mysqli_fetch_assoc($alunos)): ?>
                            <option value="<?= $aluno['id'] ?>"><?= $aluno['nome'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">Livro:</label>
                    <select class="bl-cad-input" name="id_livro" required>
                        <option value="">Selecione o livro</option>
                        <?php while ($livro = mysqli_fetch_assoc($livros)): ?>
                            <option value="<?= $livro['id'] ?>"><?= $livro['nome_livro'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">Data de Retirada:</label>
                    <input class="bl-cad-input" type="date" name="data_emprestimo" required>
                </div>

                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">Data de Devolução:</label>
                    <input class="bl-cad-input" type="date" name="data_devolucao" required>
                </div>

                <button class="bl-cad-btn" type="submit">Adicionar Empréstimo</button>
            </form>
        </div>
    </div>

</body>


</html>