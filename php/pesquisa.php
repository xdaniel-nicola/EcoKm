<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesquisa de usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container" style="padding: 0px; margin: 0px;">
        <h1>Pesquisar usuários</h1>
        <nav class="navbar bg-body-tertiary">
            <div class="container-fluid">
                <form class="d-flex" role="search" action="pesquisa.php" method="POST">
                    <input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="pesquisar" name="busca">
                    <button class="btn btn-outline-success" type="submit">Pesquisar</button>
                </form>
            </div>
        </nav>
        
<?php
$pesquisa = $_POST['busca'] ?? '';

include "conexao.php";
$pdo = conectaPDO();

if ($pdo) {
    $sql = "SELECT * FROM usuario WHERE nome LIKE :pesquisa";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':pesquisa', '%' . $pesquisa . '%');
    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($dados) {
        echo '<table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Celular1</th>
                    <th scope="col">Celular2</th>
                    <th scope="col">Data de Nascimento</th>
                    <th scope="col">Nome Materno</th>
                    <th scope="col">CEP</th>
                    <th scope="col">Logradouro</th>
                    <th scope="col">Bairro</th>
                    <th scope="col">Cidade</th>
                    <th scope="col">Sexo</th>
                    <th scope="col">Login</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($dados as $row) {
            echo '<tr>
                <td>' . htmlspecialchars($row['nome']) . '</td>
                <td>' . htmlspecialchars($row['celular1']) . '</td>
                <td>' . htmlspecialchars($row['celular2']) . '</td>
                <td>' . htmlspecialchars($row['dt_nasc']) . '</td>
                <td>' . htmlspecialchars($row['nomeMae']) . '</td>
                <td>' . htmlspecialchars($row['cep']) . '</td>
                <td>' . htmlspecialchars($row['endereco']) . '</td>
                <td>' . htmlspecialchars($row['bairro']) . '</td>
                <td>' . htmlspecialchars($row['cidade']) . '</td>
                <td>' . htmlspecialchars($row['sexo']) . '</td>
                <td>' . htmlspecialchars($row['login']) . '</td>
            </tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>Nenhum resultado encontrado.</p>';
    }
} else {
    echo '<p>Erro na conexão com o banco de dados.</p>';
}
?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
