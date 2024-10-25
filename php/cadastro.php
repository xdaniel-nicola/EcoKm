<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conta criada com sucesso!</title>
</head>
<body>
    <header>
        <h1>Sua conta foi criada!</h2>
    </header>
    <main>
        <?php
        include "conexao.php";

        function limparMascara($valor) {
            // funçao que tira tudo que não é número
            return trim(preg_replace('/[^0-9]/', '', $valor));
        }

        function prepararTexto($valor) {
            // funçao que tira os espaços antes, depois e dentro da string e converte para maiúsculas
            return strtoupper(trim(str_replace(' ', '', $valor)));
        }
        
        function formatarDataParaMySQL($dataNascimentoUsuario) {
            // Primeiro, separamos o dia, mês e ano
            $partes = explode('/', $dataNascimentoUsuario);
            if (count($partes) === 3) {
                $dia = $partes[0];
                $mes = $partes[1];
                $ano = $partes[2];
                // Em seguida, retornamos a data no formato YYYY-MM-DD
                return "$ano-$mes-$dia";
            }
            return null; // Retorna null se a data estiver vazia ou inválida
        }

        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $cpf = limparMascara($_POST['cpf']);
        $celular1 = limparMascara($_POST['celular1']);
        $celular2 = limparMascara($_POST['celular2']);
        $dt_nasc = formatarDataParaMySQL(trim($_POST['dt_nasc']));
        $cep = limparMascara($_POST['cep']);
        $nomeMae = trim($_POST['nomeMae']);
        $endereco = strtoupper(trim($_POST['endereco']));
        $login = prepararTexto($_POST['login']);
        $cidade = prepararTexto($_POST['cidade']);
        $senha = prepararTexto($_POST['senha']);
        $bairro = prepararTexto($_POST['bairro']);
        $sexo = prepararTexto($_POST['sexo']);
        // var_dump($_POST);
        
        $pdo = conectaPDO();

        if ($pdo) {
            $query = $pdo->prepare("INSERT INTO usuario (nome, email, cpf, celular1, celular2,
             dt_nasc, cep, nomeMae, endereco, login, cidade, senha, bairro, sexo) 
                        VALUES (:nome, :email, :cpf, :celular1, :celular2, :dt_nasc, :cep, :nomeMae, :endereco, :login, 
                        :cidade, :senha, :bairro, :sexo)");

            // essa parte vincula os parametros para fazer o cadastro
            $query->bindParam(':nome', $nome);
            $query->bindParam(':email', $email);
            $query->bindParam(':cpf', $cpf);
            $query->bindParam(':celular1', $celular1);
            $query->bindParam(':celular2', $celular2);
            $query->bindParam(':dt_nasc', $dt_nasc);
            $query->bindParam(':cep', $cep);
            $query->bindParam(':nomeMae', $nomeMae);
            $query->bindParam(':endereco', $endereco);
            $query->bindParam(':login', $login);
            $query->bindParam(':cidade', $cidade);
            $query->bindParam(':senha', $senha);
            $query->bindParam(':bairro', $bairro);
            $query->bindParam(':sexo', $sexo);
            
            if($query -> execute()) {
                echo "Dados inseridos com sucesso!";
            } else {
                echo "Erro ao inserir dados.";
            }
        } else {
            echo "Erro na conexão com o banco de dados.";
        }
        
        ?>

        <a href="../login/login.html">Voltar para login</a>
    </main>
</body>
</html>