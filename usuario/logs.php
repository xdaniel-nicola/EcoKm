<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Screen</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/log.css">
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo"><img src="../img/ecokmlogo3.png" width="135"></div>
            <ul class="links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../planos/planos.php">Planos</a></li>
            </ul>
            <div class="theme-toggle">
                <input type="checkbox" id="toggle-switch" class="toggle-switch">
                <label for="toggle-switch" class="toggle-label">
                <span class="toggle-slider"></span>
                </label>
                </div>
            <div class="username-container">
                <a href="perfil.php" class="action-btn">Voltar</a>
            </div>
        </div>
    </header>
    <div class="container">
        <h1>Pesquisar atividade</h1>
        <nav class="navbar">
            <div class="container-fluid">
                <form class="d-flex" role="search" action="logs.php" method="POST">
                    <input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="pesquisar" name="busca">
                    <button class="alterBtn" type="submit">Pesquisar</button>
                </form>
        </div>
        <?php
        $pesquisa = $_POST['busca'] ?? '';
        include "../php/conexao.php";
        $pdo = conectaPDO();

        if ($pdo) {
            $sql = "SELECT * FROM logUsers WHERE usuario LIKE :pesquisa";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':pesquisa', '%' . $pesquisa . '%');
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            if ($dados) {
    echo    '<table class="table table-striped" id="logTable">';
    echo        '<thead>';
    echo            '<tr>';
    echo                '<th>ID</th>';
    echo                '<th>Usuario</th>';
    echo                '<th>Ação</th>';
    echo                '<th>Data</th>';
    echo            '</tr>';
    echo       '</thead>';
    echo        '<tbody>';
           
    echo        '</tbody>';
    echo    '</table>';
    echo'</div>';
            } else {
                echo '<p>Nenhum resultado encontrado';
            }
        }
        ?>
    <script src="js/log.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>