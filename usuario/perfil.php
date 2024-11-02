<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login/loginForm.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo"><img src="../img/ecokmlogo3.png" width="135"></div>
            <ul class="links">
                <li><a href="../indexLogin.php">Home</a></li>
                <li><a href="../planos/planos.php">Planos</a></li>
            </ul>
            <div class="theme-toggle">
                <input type="checkbox" id="toggle-switch" class="toggle-switch">
                <label for="toggle-switch" class="toggle-label">
                    <span class="toggle-slider"></span>
                </label>
            </div>
            <div class="username-container">
                <a href="perfil.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a> <!-- Link para o perfil -->
                <a href="../login/logout.php" class="action-btn">Logout</a>
            </div>
            <div class="toggle-btn">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </header>
    <main>
        <h1>Perfil de <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <!-- Aqui você pode adicionar informações adicionais do usuário, como email, histórico de planos, etc. -->
    </main>
    <footer>
        <!-- Rodapé -->
    </footer>
    <script src="script.js"></script>
</body>
</html>
