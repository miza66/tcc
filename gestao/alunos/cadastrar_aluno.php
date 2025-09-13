<?php
session_start();
if (!isset($_SESSION['professor_id'])) {
    header('Location: ../../index.html');
    exit;
}

include '../../config/database.php'; // define $conn como mysqli_connect()

$mensagem = '';

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $ano = $_POST['ano'];       // vindo do select
    $sala = $_POST['sala'];     // input livre
    $email = $_POST['email'];

    // Junta ano + sala
    $serie = $ano . ' ' . strtoupper($sala); // exemplo: "1° Ano EM A"

    $stmt = mysqli_prepare($conn, "INSERT INTO aluno (nome, serie, email) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $nome, $serie, $email);

    if (mysqli_stmt_execute($stmt)) {
        $mensagem = "Aluno cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar aluno: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../../views/img/hello_kitty_PNG22.png" type="image/x-icon">
    <title>Cadastrar Aluno</title>
    <link rel="stylesheet" href="../../estilos/cadastro.css">
    <style>
        .form-linha {
            display: flex;
            gap: 10px;
        }

        .form-linha .bl-cad-form-group {
            flex: 1;
        }
    </style>
</head>

<body>

    <?php include_once __DIR__ . '/../../includes/header.php'; ?>

    <div class="pagina-header">
        <div class="bl-cad-container">
            <h1 class="bl-cad-title">Cadastrar Aluno</h1>

            <?php if ($mensagem): ?>
                <p class="bl-cad-message"><?= $mensagem ?></p>
            <?php endif; ?>

            <form class="bl-cad-form" method="POST">
                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">Nome:</label>
                    <input class="bl-cad-input" type="text" name="nome" required>
                </div>

                <div class="form-linha">
                    <div class="bl-cad-form-group">
                        <label class="bl-cad-label">Ano:</label>
                        <select class="bl-cad-input" name="ano" required>
                            <option value="">Selecione o ano</option>
                            <option value="6° Ano">6° Ano</option>
                            <option value="7° Ano">7° Ano</option>
                            <option value="8° Ano">8° Ano</option>
                            <option value="9° Ano">9° Ano</option>
                            <option value="1° Ano EM">1° Ano EM</option>
                            <option value="2° Ano EM">2° Ano EM</option>
                            <option value="3° Ano EM">3° Ano EM</option>
                        </select>
                    </div>

                    <div class="bl-cad-form-group">
                        <label class="bl-cad-label">Sala:</label>
                        <input class="bl-cad-input" type="text" name="sala" maxlength="1" style="text-transform: uppercase" placeholder="Ex: A, G, Z" required>
                    </div>
                </div>

                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">E-mail:</label>
                    <input class="bl-cad-input" type="email" name="email" required>
                </div>

                <button class="bl-cad-btn" type="submit">Cadastrar Aluno</button>
            </form>
        </div>
    </div>

</body>

</html>