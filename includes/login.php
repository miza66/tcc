<?php
// Inicia a sessão (se precisar guardar dados de login)
session_start();

// Inclui o arquivo de conexão
require_once("../config/database.php");

// Verifica se os campos foram enviados
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpf = trim($_POST["cpf"] ?? '');
    $senha = trim($_POST["senha"] ?? '');

    // Valida campos
    if (empty($cpf) || empty($senha)) {
        echo json_encode(["status" => "erro", "mensagem" => "CPF e senha são obrigatórios."]);
        exit;
    }

    // Prepara a query para evitar SQL Injection
    $stmt = $conn->prepare("SELECT id, nome, cpf, email, senha FROM professor WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Verifica a senha com hash
        if (password_verify($senha, $usuario["senha"])) {
            // Salva dados na sessão
            $_SESSION["professor_id"] = $usuario["id"];
            $_SESSION["professor_nome"] = $usuario["nome"];

            echo json_encode([
                "status" => "sucesso",
                "mensagem" => "Login realizado com sucesso!",
                "usuario" => [
                    "id" => $usuario["id"],
                    "nome" => $usuario["nome"],
                    "email" => $usuario["email"]
                ]
            ]);
        } else {
            echo json_encode(["status" => "erro", "mensagem" => "Senha incorreta."]);
        }
    } else {
        echo json_encode(["status" => "erro", "mensagem" => "CPF não encontrado."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Método inválido."]);
}
?>