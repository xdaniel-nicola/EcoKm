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
        <h1>Activity Log</h1>
        <!-- <div class="table-responsive"> -->
        <table class="table table-striped" id="logTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Ação</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dados serão inseridos aqui via JavaScript -->
            </tbody>
        </table>
    </div>
    <script src="js/log.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>