<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conta criada com sucesso!</title>
</head>
<body>
    <main>
        <?php
        include "conexao.php";

        function prepararTexto($valor) {
            return strtoupper(trim(str_replace(' ', '', $valor)));
        }
        
        function formatarDataParaMySQL($dataNascimentoUsuario) {
            $partes = explode('/', $dataNascimentoUsuario);
            if (count($partes) === 3) {
                $dia = $partes[0];
                $mes = $partes[1];
                $ano = $partes[2];
                return "$ano-$mes-$dia";
            }
            return null; 
        }

        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $cpf = trim($_POST['cpf']);
        $celular1 = trim($_POST['celular1']);
        $celular2 = trim($_POST['celular2']);
        $dt_nasc = formatarDataParaMySQL(trim($_POST['dt_nasc']));
        $cep = trim($_POST['cep']);
        $nomeMae = trim($_POST['nomeMae']);
        $endereco = trim($_POST['endereco']);
        $login = trim($_POST['login']);
        $cidade = trim($_POST['cidade']);
        $senha = $_POST['senha'];
        $bairro = trim($_POST['bairro']);
        $sexo = trim($_POST['sexo']);
        
        $pdo = conectaPDO();
            
    if ($pdo) {
        $query_email = $pdo->prepare("SELECT * FROM usuario WHERE email = :email");
        $query_email->bindParam(':email', $email);
        $query_email->execute();
    
        $query_cpf = $pdo->prepare("SELECT * FROM usuario WHERE cpf = :cpf");
        $query_cpf->bindParam(':cpf', $cpf);
        $query_cpf->execute();
    
        $query_login = $pdo->prepare("SELECT * FROM usuario WHERE login = :login");
        $query_login->bindParam(':login', $login);
        $query_login->execute();
    
        $erro_email = $query_email->rowCount() > 0;
        $erro_cpf = $query_cpf->rowCount() > 0;
        $erro_login = $query_login->rowCount() > 0;
    
        if ($erro_email || $erro_cpf || $erro_login) {
            echo "Dados inválidos, tente novamente. ";
            if ($erro_email) echo "Email já cadastrado. <a href='../cadastro/cadastro.php'>Clique aqui para voltar ao cadastro.</a>";
            if ($erro_cpf) echo "CPF já cadastrado. <a href='../cadastro/cadastro.php'>Clique aqui para voltar ao cadastro.</a>";
            if ($erro_login) echo "Login já cadastrado. <a href='../cadastro/cadastro.php'>Clique aqui para voltar ao cadastro.</a>";
        } else {

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $query_insert = $pdo->prepare("INSERT INTO usuario (nome, email, cpf, celular1, celular2,
                 dt_nasc, cep, nomeMae, endereco, login, cidade, senha, bairro, sexo) 
                 VALUES (:nome, :email, :cpf, :celular1, :celular2, :dt_nasc, :cep, :nomeMae, :endereco, :login, 
                 :cidade, :senha, :bairro, :sexo)");
    
            $query_insert->bindParam(':nome', $nome);
            $query_insert->bindParam(':email', $email);
            $query_insert->bindParam(':cpf', $cpf);
            $query_insert->bindParam(':celular1', $celular1);
            $query_insert->bindParam(':celular2', $celular2);
            $query_insert->bindParam(':dt_nasc', $dt_nasc);
            $query_insert->bindParam(':cep', $cep);
            $query_insert->bindParam(':nomeMae', $nomeMae);
            $query_insert->bindParam(':endereco', $endereco);
            $query_insert->bindParam(':login', $login);
            $query_insert->bindParam(':cidade', $cidade);
            $query_insert->bindParam(':senha', $senha_hash);
            $query_insert->bindParam(':bairro', $bairro);
            $query_insert->bindParam(':sexo', $sexo);
    
            if ($query_insert->execute()) {
                echo "<h1>Conta criada com sucesso! <a href='../login/loginForm.php'>Clique aqui para fazer login.</a></h1>";
                header("Location: ../login/loginForm.php");
            } else {
                echo "Erro ao inserir dados.";
            }
        }
    } else {
        echo "Erro na conexão com o banco de dados.";
    }
        ?>

        <!-- <a href="../login/loginForm.php">Voltar para login</a> -->
    </main>
</body>
</html>