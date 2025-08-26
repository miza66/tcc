<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Header</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background:rgb(231, 130, 171);
        }

        /* Sidebar */
        .sidebar {
            width: 380px;
            background:rgb(235, 213, 216);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .logo {
            width: 100px;
            height: 100px;
            margin-bottom: 30px;
            cursor: pointer;
        }

        .sidebar ul {
            list-style: none;
            width: 80%;
            height: 30px;
            padding: 0 10px;
        }

        .sidebar ul li {
            margin: 40px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 10px;
            color: #fff;
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            background: linear-gradient(145deg,rgb(220, 105, 195),rgb(220, 0, 147));
            transition: all 0.3s ease;
        }

        .sidebar ul li a:hover {
            background: linear-gradient(145deg,rgb(118, 8, 63),rgb(15, 6, 86));
            transform: scale(1.05);
        }

        /* Conteúdo principal */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        /* Extras fofos */
        .sidebar ul li a::before {
            content: "🌸 ";
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <img src="logo.png" alt="Logo do site" class="logo" onclick="goHome()">
        <ul>
            <li><a href="gestao_de_biblioteca/emprestimos/criar_emprestimo.php">Criar Empréstimo</a></li>
            <li><a href="gestao_de_biblioteca/livros/buscar_livro.php">Buscar Livros</a></li>
            <li><a href="gestao_de_biblioteca/alunos/cadastrar_aluno.php">Cadastrar Alunos</a></li>
            <li><a href="gestao_de_biblioteca/alunos/ver_alunos.php">Ver Alunos</a></li>
            <li><a href="gestao_de_biblioteca/professores/cadastrar_professor.php">Cadastrar Professor</a></li>
            <li><a href="gestao_de_biblioteca/professores/ver_professores.php">Ver Professores</a></li>
            <li><a href="gestao_de_biblioteca/livros/cadastrar_livro.php">Cadastrar Livro</a></li>
            <li><a href="includes/logout.php">Sair</a></li>
        </ul>
    </div>

    <div class="main-content">
        <!-- Conteúdo das páginas será exibido aqui -->
    </div>

    <script>
        function goHome() {
            window.location.href = 'dashboard.php';
        }
    </script>

</body>

</html>