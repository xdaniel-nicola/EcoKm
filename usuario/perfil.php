<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login/loginForm.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/perfil.css">
    <script src="js/perfil.js" defer></script>
    
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
            <a href="../login/logout.php" class="action-btn">Logout</a>
        </div>
    </div>
</header>
<main>
    
<div class="cabecalhoUser">
    <!-- <h1>Perfil de <?php echo htmlspecialchars($_SESSION['username']); ?></h1> -->
    <?php

    include "../php/conexao.php";
        $pdo = conectaPDO();

    if ($pdo) {
        $sql = "SELECT * FROM usuario WHERE nome = :nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $_SESSION['username'], PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($usuario['nome'] === 'admin') {
            echo '<a href="pesquisa.php" class="userList">Lista de usuários</a>';
            echo '<a href="logs.php" class="userList">Log de Usuários</a>';
    }
    ?>

</div>

    <div class="dados table-responsive">
        
        <?php
            // include "../php/conexao.php";
            // $pdo = conectaPDO();
            // if ($pdo) {
            //     $sql = "SELECT * FROM usuario WHERE nome = :nome";
            //     $stmt = $pdo->prepare($sql);
            //     $stmt->bindValue(':nome', $_SESSION['username'], PDO::PARAM_STR);
            //     $stmt->execute();
            //     $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                

                if ($usuario) {
                echo '<div class="dadosUser table-responsive">';
                        $camposPersonalizados = [
                            'dt_nasc' => 'Data de Nascimento',
                            'nomeMae' => 'Nome Materno',
                            'cpf' => 'CPF',
                            'cep' => 'CEP',
                            'endereco' => 'Endereço'
                        ];
                    
                        echo '<h2>Dados do Usuário</h2>';
                        echo '<table class="table table-striped ">';
                        foreach ($usuario as $key => $value) {
                            if ($key === 'senha') {
                                continue;
                            }
                            $nomeExibicao = isset($camposPersonalizados[$key]) ? $camposPersonalizados[$key] : ucfirst(str_replace("_", " ", $key));
                            echo '<tr>';
                            echo '<th>' . $nomeExibicao . '</th>';
                            echo '<td>' . htmlspecialchars($value) . '</td>';
                            echo '</tr>';
                        } if ($key === 'sexo') {
                            echo '<tr>';
                            echo '<th></th>';
                            echo '<td><a class="alterBtn" href="atualizar.php?nome=' . $usuario['nome'] . '">Atualizar dados</a></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                echo '</div>';
                $sqlPlano = "SELECT * FROM plano WHERE cpf = :cpf";
                $stmtPlano = $pdo->prepare($sqlPlano);
                $stmtPlano->bindValue(':cpf', $usuario['cpf'], PDO::PARAM_STR);
                $stmtPlano->execute();
                $planos = $stmtPlano->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<div class="planosAtivos">';
                echo '<h2>Plano Ativo</h2>';
                
                if ($planos) {
                    echo '<table class="table table-striped">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Plano</th>';
                    echo '<th>Preço (R$)</th>';
                    echo '<th></th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ($planos as $plano) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($plano['tipo']) . '</td>';
                        echo '<td>' . htmlspecialchars($plano['preco']) . '</td>';
                        echo '<td>
                                <form method="POST" action="perfil.php" id="formCancelamento">
                                    <input type="hidden" name="id_plano" value="' . htmlspecialchars($plano['id_plano']) . '">
                                    <button type="button" class="cancelaPlano" id="cancelarBtn">Cancelar plano</button>
                                </form>
                                </td>';
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                    
                    echo '</div>';
                        } else {
                            echo '<p>Não há plano.</p>';
                            echo '<a class="alterBtn" href="../planos/planos.php">Assinar plano</a>';
                        }

                        } else {
                            echo '<p>Nenhum resultado encontrado.</p>';
                        }
                    } else {
                        echo '<p>Erro na conexão com o banco de dados.</p>';
                    }
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_plano'])) {
                        $idPlano = $_POST['id_plano'];
                    
                    
                        if ($pdo) {
                            $sqlCancel = "UPDATE plano SET cpf = NULL WHERE id_plano = :id_plano";
                            $stmtCancel = $pdo->prepare($sqlCancel);
                            $stmtCancel->bindValue(':id_plano', $idPlano, PDO::PARAM_INT);
                            $stmtCancel->execute();
                            registraLog($pdo, $_SESSION['username'], "Cancelou o plano assinado");
                            header("Location: perfil.php?msg=Plano cancelado com sucesso");
                            exit();
                        }
                    }
                    echo '</div>';


                    $sqlViagem = "SELECT * FROM viagem WHERE cpf = :cpf";
                    $stmtViagem = $pdo->prepare($sqlViagem);
                    $stmtViagem->bindValue(':cpf', $usuario['cpf'], PDO::PARAM_STR);
                    $stmtViagem->execute();
                    $viagens = $stmtViagem->fetchAll(PDO::FETCH_ASSOC);

                    echo '<div id="viagens" class="viagemSalva table-responsive">';
                    echo '<h2>Viagens Salvas</h2>';

                    if ($viagens) {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped">';
                        echo '<thead>';
                        echo '<tr>';
                        // echo '<th>Id</th>';
                        echo '<th>Tipo de Motor</th>';
                        echo '<th>Tipo de Combustível</th>';
                        echo '<th>Distância</th>';
                        echo '<th>Preço do Combustível</th>';
                        echo '<th>Partida</th>';
                        echo '<th>Chegada</th>';
                        echo '<th>Tempo Estimado</th>';
                        echo '<th>Consumo</th>';
                        echo '<th>Custo</th>';
                        echo '<th></th>';
                        echo '</tr>';
                        echo '</thead>';
                        
                        echo '<tbody>';
                        foreach ($viagens as $viagem) {
                            echo '<tr>';
                            // echo '<td>' . htmlspecialchars($viagem['id_viagem'] ?? 'Não informado') . '</td>';
                            echo '<td>' . htmlspecialchars($viagem['tipoMotor'] ?? 'Não informado') . '</td>';
                            echo '<td>' . htmlspecialchars($viagem['tipoCombustivel'] ?? 'Não informado') . '</td>';
                            echo '<td>' . htmlspecialchars($viagem['distancia'] ?? 'Não informado') . 'km' . '</td>';
                            echo '<td>'. 'R$' . htmlspecialchars($viagem['precoCombustivel'] ?? 'Não informado') . '</td>';
                            echo '<td>' . htmlspecialchars($viagem['partida'] ?? 'Não informado') . '</td>';
                            echo '<td>' . htmlspecialchars($viagem['chegada'] ?? 'Não informado') . '</td>';
                            echo '<td>' . htmlspecialchars($viagem['tempoEstimado'] ?? 'Não informado') . '</td>';
                            echo '<td>' . htmlspecialchars($viagem['consumo'] ?? 'Não informado') . 'L' . '</td>';
                            echo '<td>'. 'R$' . htmlspecialchars($viagem['custo'] ?? 'Não informado') . '</td>';
                            echo '<td><a class="alterBtn" href="apagarViagem.php?id= '.$viagem["id_viagem"].'">Apagar viagem</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<p>Não há viagens salvas.</p>';
                        echo '<a class="alterBtn" href="../index.php#calculadora">Calculadora</a>';
                    }

                    echo '</div>';
                    echo '</div>';
                    ?>
            <div id="modalCancelar" class="modal">
                <div class="modal-content">
                <span class="close" id="closeModal">&times;</span>
                <h2>Tem certeza que deseja cancelar o plano?</h2>
                <form id="formCancelamento" method="POST" action="perfil.php">
                <input type="hidden" name="id_plano" id="id_plano_modal">
                <button class="modalBtn" type="button" id="simCancelarBtn">Sim, cancelar</button>
                <button class="modalBtn" type="button" id="voltarBtn">Voltar</button>
                </form>
                </div>
            </div>
</main>

    
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   
</body>
</html>
