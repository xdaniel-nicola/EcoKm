<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: perfil.php');
    exit();
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesquisa de usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/pesquisa.css">
    <script src="js/pesquisa.js" defer></script>
</head>
<body>
<header>
    <div class="navbar">
        <div class="logo"><img src="../img/ecokmlogo3.png" width="135"></div>
        <ul class="links">
            <li><a href="../index.php">Home</a></li>
            <li><a href="../planos/planos.php">Planos</a></li>
        </ul>
        <!-- Botão de alternância de tema -->
        <div class="theme-toggle">
            <input type="checkbox" id="toggle-switch" class="toggle-switch">
            <label for="toggle-switch" class="toggle-label">
            <span class="toggle-slider"></span>
            </label>
            </div>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="username-container">
                <ul class="links">
                    <li><a class="usuario" href="perfil.php">Bem vindo, <?php echo htmlspecialchars($_SESSION['username']);?></a></li>
                    <li><a href="../login/logout.php" class="action-btn">Logout</a></li>
                </ul>
                </div>
        <?php endif ?>
    </div>
</header>
    <div class="container">
        <h1>Pesquisar usuários</h1>
        <nav class="navbar">
            <div class="container-fluid">
                <form class="d-flex" role="search" action="pesquisa.php" method="POST">
                    <input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="pesquisar" name="busca">
                    <button class="alterBtn" type="submit">Pesquisar</button>
                </form>
            </div>
        </nav>
        
        <?php
$pesquisa = $_POST['busca'] ?? '';

include "../php/conexao.php";
$pdo = conectaPDO();

if ($pdo) {
    $sql = "SELECT * FROM usuario WHERE nome LIKE :pesquisa AND login != 'admin'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':pesquisa', '%' . $pesquisa . '%');
    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($dados) {
        echo '<div class=table-responsive>';
            echo '<table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>CPF</th>
                        <th>Celular1</th>
                        <th>Celular2</th>
                        <th>Data de Nascimento</th>
                        <th>Nome Materno</th>
                        <th>CEP</th>
                        <th>Endereço</th>
                        <th>Sexo</th>
                        <th>Login</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($dados as $row) {
            echo '<tr>
                <td>' . htmlspecialchars($row['nome']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['cpf']) . '</td>
                <td>' . htmlspecialchars($row['celular1']) . '</td>
                <td>' . htmlspecialchars($row['celular2']) . '</td>
                <td>' . htmlspecialchars($row['dt_nasc']) . '</td>
                <td>' . htmlspecialchars($row['nomeMae']) . '</td>
                <td>' . htmlspecialchars($row['cep']) . '</td>
                <td>' . htmlspecialchars($row['endereco']) . '</td>
                <td>' . htmlspecialchars($row['sexo']) . '</td>
                <td>' . htmlspecialchars($row['login']) . '</td>
                <td><a class="alterBtn" href="../php/apagarUsuario.php?id=' . htmlspecialchars($row["cpf"]) . '">Excluir</a></td>
            </tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
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
</html>