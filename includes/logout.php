<?php
// Inicia a sessão
session_start();

// Remove todas as variáveis de sessão
$_SESSION = array();

// Se existe um cookie de sessão, destrua-o também
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// Redireciona para a página de login ou home
header("Location: ../index.html");
exit;
?>