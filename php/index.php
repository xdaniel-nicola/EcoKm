<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Teste de Conexão com Banco de Dados MySql</h1>
    <?php
        require_once "conexao.php";

        $conn = conectaPDO();
        $stmt = $conn->prepare('SELECT * FROM usuario');
        $stmt-> execute();


        echo "<table border='1'>";
        echo "<tr>";
        echo "<td><strong>Nome</strong></td>";
        echo "<td><strong>Email</strong></td>";
        echo "<td><strong>CPF</strong></td>";
        echo "<td><strong>Celular1</strong></td>";
        echo "<td><strong>Celular2</strong></td>";
        echo "<td><strong>Data de Nascimento</strong></td>";
        echo "<td><strong>CEP</strong></td>";
        echo "<td><strong>Nome Materno</strong></td>";
        echo "<td><strong>Logradouro</strong></td>";
        echo "<td><strong>Login</strong></td>";
        echo "<td><strong>Cidade</strong></td>";
        echo "<td><strong>Senha</strong></td>";
        echo "<td><strong>Bairro</strong></td>";
        echo "<td><strong>Sexo</strong></td>";
        echo "</tr>";

        while($registro = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>".$registro["nome"] . "</td>" .
                "<td>".$registro["email"] . "</td>" .
                "<td>".$registro["cpf"] . "</td>" .
                "<td>".$registro["celular1"] . "</td>" .
                "<td>".$registro["celular2"] . "</td>" .
                "<td>".$registro["dt_nasc"] . "</td>" .
                "<td>".$registro["cep"] . "</td>" .
                "<td>".$registro["nomeMae"] . "</td>" .
                "<td>".$registro["endereco"] . "</td>" .
                "<td>".$registro["login"] . "</td>" .
                "<td>".$registro["cidade"] . "</td>" .
                "<td>".$registro["senha"] . "</td>" .
                "<td>".$registro["bairro"] . "</td>" .
                "<td>".$registro["sexo"] . "</td>" ;
            echo "</tr>";
        }

        echo "</table>";
    ?>
</body>
</html>