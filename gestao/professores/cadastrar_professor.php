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
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Remove a máscara do CPF
    $cpf = preg_replace('/\D/', '', $cpf); // remove tudo que não for número

    if (strlen($senha) > 16) {
        $mensagem = "A senha deve ter no máximo 16 caracteres.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "INSERT INTO professor (nome, cpf, email, senha) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nome, $cpf, $email, $senha_hash);

        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "Professor cadastrado com sucesso!";
        } else {
            if (mysqli_errno($conn) === 1062) {
                $mensagem = "CPF ou email já cadastrado.";
            } else {
                $mensagem = "Erro ao cadastrar professor: " . mysqli_error($conn);
            }
        }

        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Professor</title>
    <link rel="stylesheet" href="../../estilos/cadastro.css">
</head>

<body>

    <?php include_once __DIR__ . '/../../includes/header.php'; ?>

    <div class="pagina-header">
        <div class="bl-cad-container">
            <h1 class="bl-cad-title">Cadastrar Professor</h1>

            <?php if ($mensagem): ?>
                <p class="bl-cad-message"><?= $mensagem ?></p>
            <?php endif; ?>

            <form class="bl-cad-form" method="POST">
                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">Nome:</label>
                    <input class="bl-cad-input" type="text" name="nome" required>
                </div>

                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">CPF:</label>
                    <input class="bl-cad-input" type="text" name="cpf" maxlength="14" id="cpfInput" required>
                </div>

                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">Email:</label>
                    <input class="bl-cad-input" type="email" name="email" required>
                </div>

                <div class="bl-cad-form-group">
                    <label class="bl-cad-label">Senha (até 16 caracteres):</label>
                    <input class="bl-cad-input" type="text" name="senha" maxlength="16" required>
                </div>

                <button class="bl-cad-btn" type="submit">Cadastrar Professor</button>
            </form>
        </div>
    </div>

    <script>
        const cpfInput = document.querySelector('input[name="cpf"]');

        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // remove tudo que não é número
            if (value.length > 11) value = value.slice(0, 11); // limita a 11 dígitos

            // Formata como 000.000.000-00
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

            e.target.value = value;
        });
    </script>


</body>

</html>