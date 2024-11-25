<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carro cadastrado com sucesso!</title>
</head>
<body>
    <main>
        <?php
        include "conexao.php";

        $modelo = trim($_POST['modelo']);
        $marca = trim($_POST['marca']);
        $motor = trim($_POST['motor']);
        $cpf = trim($_POST['cpf']);
        
        $pdo = conectaPDO();
            
    if ($pdo) {
            $query_insert = $pdo->prepare("INSERT INTO carro (id_carro, modelo, marca, motor, cpf) 
                 VALUES (null, :modelo, :marca, :motor, :cpf)");
    
            $query_insert->bindParam(':modelo', $modelo);
            $query_insert->bindParam(':marca', $marca);
            $query_insert->bindParam(':motor', $motor);
            $query_insert->bindParam(':cpf', $cpf);
    
            if ($query_insert->execute()) {
                echo "Carro cadastrado com sucesso! <a href='../usuario/perfil.php'>Clique aqui para voltar ao perfil.</a>";
            } else {
                echo "Erro ao inserir dados.";
            }
        }
    else {
        echo "Erro na conexão com o banco de dados.";
    }
        ?>

        <!-- <a href="../login/loginForm.php">Voltar para login</a> -->
    </main>
</body>
</html>