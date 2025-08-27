<?php
// Conexão com o banco
require_once("../../config/database.php");

// fuso horário
date_default_timezone_set('America/Sao_Paulo');


// Consulta 1: Alunos que mais leram
$sqlAlunos = "SELECT a.nome AS aluno_nome, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 2
              GROUP BY e.id_aluno
              ORDER BY total DESC
              LIMIT 5";
$resultAlunos = $conn->query($sqlAlunos);
$temAlunos = $resultAlunos->num_rows > 0;

// Consulta 2: Livros mais lidos
$sqlLivros = "SELECT l.nome_livro, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN livro l ON e.id_livro = l.id
              WHERE e.status = 2
              GROUP BY e.id_livro
              ORDER BY total DESC
              LIMIT 5";
$resultLivros = $conn->query($sqlLivros);
$temLivros = $resultLivros->num_rows > 0;

// Consulta 3: Séries que mais leram
$sqlSeries = "SELECT a.serie, COUNT(e.id) AS total
              FROM emprestimo e
              JOIN aluno a ON e.id_aluno = a.id
              WHERE e.status = 2
              GROUP BY a.serie
              ORDER BY total DESC
              LIMIT 5";
$resultSeries = $conn->query($sqlSeries);
$temSeries = $resultSeries->num_rows > 0;

// Consulta para observações
$sqlNotas = "SELECT n.id, n.texto, n.data, p.nome AS professor_nome, CONVERT_TZ(data, '+00:00', '+00:00') AS data_corrigida
             FROM anotacoes n
             JOIN professor p ON n.id_professor = p.id
             ORDER BY n.data DESC";
$resultNotas = $conn->query($sqlNotas);
$temNotas = $resultNotas->num_rows > 0;
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatórios de Leitura</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="../../estilos/relatorios.css">
</head>

<body class="relatorios-body">

    <?php include_once __DIR__ . '/../../includes/header.php'; ?>

    <div class="pagina-header">
        <div class="relatorios-main-content">
            <div class="relatorios-chart-container">
                <h2 class="relatorios-chart-title">Alunos que mais leram</h2>
                <div id="chartAlunos" class="relatorios-chart"></div>
            </div>

            <div class="relatorios-chart-container">
                <h2 class="relatorios-chart-title">Livros mais lidos</h2>
                <div id="chartLivros" class="relatorios-chart"></div>
            </div>

            <div class="relatorios-chart-container">
                <h2 class="relatorios-chart-title">Séries que mais leram</h2>
                <div id="chartSeries" class="relatorios-chart"></div>
            </div>
        </div>

        <div class="relatorios-sidebar">
            <h2 class="relatorios-sidebar-title">Anotações dos Professores</h2>
            <?php if ($temNotas): ?>
                <?php while ($nota = $resultNotas->fetch_assoc()): ?>
                    <div class="relatorios-note">
                        <strong class="relatorios-note-author"><?= htmlspecialchars($nota['professor_nome']) ?></strong><br>
                        <em class="relatorios-note-date"><?= date('d/m/Y H:i', strtotime($nota['data_corrigida'])) ?></em>
                        <p class="relatorios-note-text"><?= nl2br(htmlspecialchars($nota['texto'])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="relatorios-no-notes">Nenhuma anotação disponível.</p>
            <?php endif; ?>
            <div class="relatorios-sidebar-footer">
                <a href="fazer_anotacao.php" class="relatorios-add-note-button">Adicionar Anotação</a>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawRelatoriosCharts);

        function drawRelatoriosCharts() {
            // Alunos
            var dataAlunos = google.visualization.arrayToDataTable([
                ['Aluno', 'Total'],
                <?php
                if ($temAlunos) {
                    while ($row = $resultAlunos->fetch_assoc()) {
                        echo "['" . addslashes($row['aluno_nome']) . "', " . $row['total'] . "],";
                    }
                }
                ?>
            ]);
            var optionsAlunos = {
                pieHole: 1,
                legend: {
                    position: 'bottom'
                },
                height: 500
            };
            var chartAlunos = new google.visualization.PieChart(document.getElementById('chartAlunos'));
            chartAlunos.draw(dataAlunos, optionsAlunos);

            // Livros
            var dataLivros = google.visualization.arrayToDataTable([
                ['Livro', 'Total'],
                <?php
                if ($temLivros) {
                    while ($row = $resultLivros->fetch_assoc()) {
                        echo "['" . addslashes($row['nome_livro']) . "', " . $row['total'] . "],";
                    }
                }
                ?>
            ]);
            var optionsLivros = {
                pieHole: 1,
                legend: {
                    position: 'bottom'
                },
                height: 500
            };
            var chartLivros = new google.visualization.PieChart(document.getElementById('chartLivros'));
            chartLivros.draw(dataLivros, optionsLivros);

            // Séries
            var dataSeries = google.visualization.arrayToDataTable([
                ['Série', 'Total'],
                <?php
                if ($temSeries) {
                    while ($row = $resultSeries->fetch_assoc()) {
                        echo "['" . addslashes($row['serie']) . "', " . $row['total'] . "],";
                    }
                }
                ?>
            ]);
            var optionsSeries = {
                pieHole: 1,
                legend: {
                    position: 'bottom'
                },
                height: 500
            };
            var chartSeries = new google.visualization.PieChart(document.getElementById('chartSeries'));
            chartSeries.draw(dataSeries, optionsSeries);
        }
    </script>
</body>

</html>