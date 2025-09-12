<?php
session_start();
require_once("../../config/database.php");
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['professor_id']) || !is_numeric($_SESSION['professor_id'])) {
    die("Erro: Professor não identificado. Faça login novamente.");
}

$id_professor = intval($_SESSION['professor_id']);


// Valida se o professor realmente existe no banco
$stmtCheck = $conn->prepare("SELECT id FROM professor WHERE id = ?");
$stmtCheck->bind_param("i", $id_professor);
$stmtCheck->execute();
$stmtCheck->store_result();
if ($stmtCheck->num_rows === 0) {
    die("Erro: Professor inválido.");
}
$stmtCheck->close();

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $texto = trim($_POST['texto']);

    if (!empty($texto)) {
        $stmt = $conn->prepare("INSERT INTO anotacoes (texto, id_professor, data) VALUES (?, ?, NOW())");
        $stmt->bind_param("si", $texto, $id_professor);
        if ($stmt->execute()) {
            header("Location: relatorios.php");
            exit;
        } else {
            $erro = "Erro ao salvar a anotação: " . $conn->error;
        }
        $stmt->close();
    } else {
        $erro = "O campo de anotação não pode estar vazio.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../../views/img/hello_kitty_PNG22.png" type="image/x-icon">
    <title>Adicionar Anotação</title>
    <link rel="stylesheet" href="../../estilos/relatorios.css">
    <style>
        .relatorios-main-content {
            max-width: 700px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            font-size: 1.8em;
            color: #333;
            text-align: center;
        }

        .erro-msg {
            background: #ffe0e0;
            color: #c00;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        textarea {
            width: 100%;
            padding: 15px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
            min-height: 150px;
            transition: border-color 0.3s;
        }

        textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .relatorios-add-note-button {
            display: inline-block;
            padding: 12px 25px;
            margin-top: 15px;
            margin-right: 10px;
            background-color: #9707aa;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .relatorios-add-note-button:hover {
            background-color: #620b6dff;
            transform: translateY(-2px);
        }

        .relatorios-add-note-button.cancel {
            background-color: #888;
        }

        .relatorios-add-note-button.cancel:hover {
            background-color: #666;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form div {
            margin-top: 10px;
        }

        .relatorios-add-note-button:disabled {
            background-color: #999;
            cursor: not-allowed;
            transform: none;
        }
    </style>
</head>

<body class="relatorios-body">

    <?php include_once __DIR__ . '/../../includes/header.php'; ?>

    <div class="pagina-header">
        <div class="relatorios-main-content">
            <h2>Adicionar Anotação</h2>

            <?php if (!empty($erro)): ?>
                <p class="erro-msg"><?= htmlspecialchars($erro) ?></p>
            <?php endif; ?>

            <form id="anotacaoForm" method="post">
                <label for="texto">Texto da Anotação:</label>
                <textarea name="texto" id="texto" rows="6" required></textarea>

                <div>
                    <button type="submit" class="relatorios-add-note-button" id="btnSalvar">Salvar Anotação</button>
                    <button type="button" class="relatorios-add-note-button cancel" id="btnCancelar">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Cancelar redireciona imediatamente
        document.getElementById('btnCancelar').addEventListener('click', function() {
            window.location.href = 'relatorios.php';
        });

        // Feedback ao salvar
        document.getElementById('anotacaoForm').addEventListener('submit', function() {
            var btn = document.getElementById('btnSalvar');
            btn.disabled = true;
            btn.textContent = 'Salvando…';
        });
    </script>

</body>

</html>