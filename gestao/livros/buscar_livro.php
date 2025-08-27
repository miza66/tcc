<?php
require_once __DIR__ . '/../../config/database.php'; // ajuste conforme seu caminho real

// Função para buscar livros na API do Google Books
function buscarLivrosGoogle($query)
{
    $query = urlencode("intitle:" . $query);
    $url = "https://www.googleapis.com/books/v1/volumes?q={$query}&maxResults=20";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (!isset($data['items'])) return [];

    $livros = [];
    foreach ($data['items'] as $item) {
        $volumeInfo = $item['volumeInfo'];
        $livro = [
            'nome_livro' => $volumeInfo['title'] ?? '',
            'nome_autor' => isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : '',
            'isbn' => '',
            'capa' => $volumeInfo['imageLinks']['thumbnail'] ?? ''
        ];

        if (isset($volumeInfo['industryIdentifiers'])) {
            foreach ($volumeInfo['industryIdentifiers'] as $id) {
                if ($id['type'] === 'ISBN_13' || $id['type'] === 'ISBN_10') {
                    $livro['isbn'] = $id['identifier'];
                    break;
                }
            }
        }

        $livros[] = $livro;
    }

    return $livros;
}

// Função para salvar livro no banco (MySQLi)
function salvarLivro($conn, $livro)
{
    $stmt = $conn->prepare("INSERT INTO livro (nome_livro, nome_autor, isbn) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Erro ao preparar statement: " . $conn->error);
    }

    $stmt->bind_param(
        "sss",
        $livro['nome_livro'],
        $livro['nome_autor'],
        $livro['isbn']
    );
    $stmt->execute();
    $stmt->close();
}

$livrosEncontrados = [];
$erro = '';
$mensagemSalvo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['salvar'])) {
        $livroParaSalvar = [
            'nome_livro' => $_POST['nome_livro'],
            'nome_autor' => $_POST['nome_autor'],
            'isbn' => $_POST['isbn']
        ];
        salvarLivro($conn, $livroParaSalvar);
        $mensagemSalvo = "Livro '{$livroParaSalvar['nome_livro']}' salvo com sucesso!";
    } else {
        $query = trim($_POST['q'] ?? '');
        if ($query === '') {
            $erro = 'Digite um termo para busca';
        } else {
            $livrosEncontrados = buscarLivrosGoogle($query);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Buscar Livros</title>
    <link rel="stylesheet" href="../../estilos/livrosbusca.css">
</head>

<body class="bl-body">

    <?php include_once __DIR__ . '/../../includes/header.php'; ?>

    <div class="pagina-header">
        <h1 class="bl-titulo-principal">🌸 Buscar Livros 🌸</h1>

        <form class="bl-form-busca" method="POST" action="">
            <input class="bl-input-texto" type="text" name="q" placeholder="Digite nome ou ISBN do livro" value="<?= htmlspecialchars($_POST['q'] ?? '') ?>">
            <button class="bl-botao-buscar" type="submit">Buscar</button>
        </form>

        <?php if (!empty($erro)): ?>
            <p class="bl-mensagem-erro" style="color:red"><?= htmlspecialchars($erro) ?></p>
        <?php endif; ?>

        <?php if (!empty($mensagemSalvo)): ?>
            <p class="bl-mensagem-sucesso" style="color:green"><?= htmlspecialchars($mensagemSalvo) ?></p>
        <?php endif; ?>

        <?php if ($livrosEncontrados): ?>
            <h2 class="bl-titulo-resultados" style="text-align: center; color: #680c7a;">Resultados encontrados:</h2>
            <ul class="bl-lista-livros">
                <?php foreach ($livrosEncontrados as $livro): ?>
                    <li class="bl-item-livro">
                        <?php if (!empty($livro['capa'])): ?>
                            <img class="bl-img-capa" src="<?= htmlspecialchars($livro['capa']) ?>" alt="Capa do livro">
                        <?php endif; ?>
                        <div class="bl-conteudo-livro">
                            <strong class="bl-nome-livro"><?= htmlspecialchars($livro['nome_livro']) ?></strong><br>
                            <span class="bl-autor-livro">Autor: <?= htmlspecialchars($livro['nome_autor'] ?: 'Desconhecido') ?></span><br>
                            <span class="bl-isbn-livro">ISBN: <?= htmlspecialchars($livro['isbn'] ?: 'Sem ISBN') ?></span><br>

                            <form class="bl-form-salvar" method="POST" action="">
                                <input type="hidden" name="nome_livro" value="<?= htmlspecialchars($livro['nome_livro']) ?>">
                                <input type="hidden" name="nome_autor" value="<?= htmlspecialchars($livro['nome_autor']) ?>">
                                <input type="hidden" name="isbn" value="<?= htmlspecialchars($livro['isbn']) ?>">
                                <button class="bl-botao-salvar" type="submit" name="salvar">Salvar no banco</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
</body>

</html>