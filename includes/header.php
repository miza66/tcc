<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Header</title>
    <style>
        /*Fonte fofinha geral */
        @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap');

        /* Reset básico só dentro do header */
        .my-header {
            font-family: 'Comic Neue', cursive;
        }

        .pagina-header {
            margin-left: 380px;
        }

        /* Sidebar */
        .my-header__sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 380px;
            height: 100vh;
            background: rgb(235, 213, 216);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 5000;
        }

        .my-header__logo {
            width: 170px;
            height: 100px;
            margin-bottom: 30px;
            cursor: pointer;
        }

        .my-header__menu {
            list-style: none;
            width: 80%;
            padding: 0;
        }

        .my-header__menu-item {
            margin: 40px 0;
        }

        .my-header__menu-item a {
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 10px;
            color: #fff;
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            background: linear-gradient(145deg, rgb(220, 105, 195), rgb(220, 0, 147));
            transition: all 0.3s ease;
        }

        .my-header__menu-item a:hover {
            background: linear-gradient(145deg, rgb(118, 8, 63), rgb(15, 6, 86));
            transform: scale(1.05);
        }

        .my-header__menu-item a::before {
            content: "🌸 ";
        }

        /* Conteúdo principal */
        .my-header__main-content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="my-header">
        <div class="my-header__sidebar">
            <img src="/tcc/views/img/Hello-Kitty-Logo.png" alt="Logo do site" class="my-header__logo" onclick="goHome()">
            <ul class="my-header__menu">
                <li class="my-header__menu-item"><a href="/tcc/dashboard.php">Página Inicial</a></li>
                <li class="my-header__menu-item"><a href="/tcc/gestao/emprestimos/criar_emprestimo.php">Criar Empréstimo</a></li>
                <li class="my-header__menu-item"><a href="/tcc/gestao/livros/buscar_livro.php">Buscar Livros</a></li>
                <li class="my-header__menu-item"><a href="/tcc/gestao/alunos/cadastrar_aluno.php">Cadastrar Alunos</a></li>
                <li class="my-header__menu-item"><a href="/tcc/gestao/alunos/ver_alunos.php">Ver Alunos</a></li>
                <li class="my-header__menu-item"><a href="/tcc/gestao/professores/cadastrar_professor.php">Cadastrar Professor</a></li>
                <li class="my-header__menu-item"><a href="/tcc/gestao/professores/ver_professores.php">Ver Professores</a></li>
                <li class="my-header__menu-item"><a href="/tcc/gestao/relatorios/relatorios.php">Relatórios</a></li>
                <li class="my-header__menu-item"><a href="/tcc/includes/logout.php">Sair</a></li>
            </ul>
        </div>

        <div class="my-header__main-content">
            <!-- Conteúdo das páginas será exibido aqui -->
        </div>
    </div>

    <script>
        function goHome() {
            window.location.href = '/tcc/dashboard.php';
        }
    </script>

</body>

</html>