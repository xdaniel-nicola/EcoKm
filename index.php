<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$loggedInClass = isset($_SESSION['username']) ? 'logged-in' : 'blur';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Calculadora de Gasto de Combustível</title>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3NZISEAjOEfUocgQXVpigSVriLgwKpCI&libraries=places&callback=initMap" async defer></script> -->
    <link rel="stylesheet" href="mapagoogle/mapa.css">
    <script src="mapagoogle/mapa.js" defer></script>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <link rel="stylesheet" href="style.css">
</head>
<body class="<?php echo $loggedInClass; ?>">
    <header>
        <div class="navbar">
            <div class="logo"><img src="img/ecokmlogo3.png" width="135"></div>
            <ul class="links">
                <li><a href="index.php">Home</a></li>
                <li><a href="planos/planos.php">Planos</a></li>
            </ul>
            <div class="theme-toggle">
            <input type="checkbox" id="toggle-switch" class="toggle-switch">
            <label for="toggle-switch" class="toggle-label">
            <span class="toggle-slider"></span>
            </label>
            </div>
            <?php if (isset($_SESSION['username'])): ?>
                <div class="username-container">
                <ul class="links">
                    <li><a class="usuario" href="usuario/perfil.php">Bem vindo, <?php echo htmlspecialchars($_SESSION['username']);?></a></li>
                    <li><a href="login/logout.php" class="action-btn">Logout</a></li>
                </ul>
                </div>
            <?php else: ?>
                <a href="login/loginForm.php" class="action-btn">Login</a>
            <?php endif; ?>
            <div class="toggle-btn">
                <i class="fa-solid fa-bars"></i>
            </div>
            </div>
        </div>
    </header>
    <main>
        <div class="carousel-container">
            <div class="carousel">
                <div class="slide"><img src="img/7.webp" alt="Imagem 1"></div>
                <div class="slide"><img src="img/8.webp" alt="Imagem 2"></div>
                <div class="slide"><img src="img/9.webp" alt="Imagem 3"></div>
            </div>
        </div>
        <section class="containerTexto1" id="containerTexto">
            <h2>Como se faz o cálculo de consumo de combustível por km?</h2>
            <p>Para fazer o cálculo do consumo de combustível por km é simples, você vai dividir o valor do litro da gasolina pelo consumo médio do veículo.</p>
            <h4>Um exemplo:</h4>
            <ul>
                <li>Digamos que a gasolina custa R$ 5,20 na sua cidade;</li>
                <li>Seu carro roda 13 km com um litro;</li>
                <li>Dessa maneira a conta seria a seguinte: 5,20/13 = 0,40;</li>
                <li>Ou seja, R$ 0,40 é o valor gasto por quilômetro.</li>
            </ul>
        </section>
        <section class="containerTexto2" id="containerTexto">
            <h2>Como é feito o cálculo de combustível?</h2>
            <p>Para ter noção do quanto o seu veículo vai consumir e conseguir realizar o cálculo de gasto de combustível, primeiro precisamos 
            saber quantos quilômetros o veículo faz com 1 litro.</p>
            <p>Com as informações de quantos km o veículo faz com um litro, quantos quilômetros serão rodados
            e o valor que foi pago por cada litro, é possível calcular quanto vai gastar de combustível na viagem ou na rotina do dia a dia, por exemplo.</p>
        </section>
        <section class="containerTexto3" id="containerTexto">
            
            <h2>Como utilizar a calculadora de combustível</h2>
                <h4>Para saber como que se faz o cálculo é bem simples, veja o passo a passo a seguir:</h4>
            <ul>
                <li>Informe a motorização do veículo,</li>
                <li>Informe o tipo de combustível que será utilizado,</li>
                <li>Digite o endereço de partida e o de chegada e clique em "Traçar Rota",</li>
                <li>Digite o valor atual do combustível selecionado,</li>
                <li>Clique em "Calcular" e o resultado aparecerá logo abaixo,</li>
                <li>Você receberá o cálculo com valores aproximados de quanto será gasto de combustível (em litros) e quanto você gastará (em reais).</li>
                <li>Caso queira (e caso esteja logado também), você também pode salvar essa viagem no seu perfil.</li>
            </ul>
        </section>
        <div class="container" id="calculadora">
            <h1>Calculadora de Gasto de Combustível</h1>
            <div id="messageContainer" style="display: none; color: red; font-weight: bold;"></div>
            <?php if($loggedInClass === 'logged-in'): ?>
                <p id="avisoMapa">Caso o mapa não esteja aparecendo basta recarregar a página.</p>
            <?php else: ?>
                <p id="avisoMapa">Caso o mapa não esteja aparecendo basta recarregar a página</p>
                <p id="logarMapa">Para utilizar o mapa é necessário estar logado.</p>
            <?php endif; ?>
                <div id="map" class="map <?php echo $loggedInClass; ?>"></div>
            <form class="card" id="form-carro" action="php/salvarRota.php" method="POST">
                <div class="containerFormCarro">
                        <div>
                            <label for="modelo-carro">Modelo do Motor:</label>
                            <select class="selecao" name="tipoMotor" id="modelo-carro"></select>
                        </div>
                        <div>
                            <label for="tipo-combustivel">Tipo de Combustível:</label>
                            <select class="selecao" name="tipoCombustivel" id="tipo-combustivel">
                                <option value="10">Gasolina</option>
                                <option value="8">Etanol</option>
                                <option value="15">GNV</option>
                            </select>
                        </div>
                        <div>
                            <label for="distancia">Distância (em km):</label>
                            <input class="selecao" type="float" name="distancia" id="distancia" required>
                        </div>
                        <div>
                            <label for="preco-combustivel">Preço do Combustível (R$):</label>
                            <input class="selecao" type="number" name="precoCombustivel" id="preco-combustivel" step="0.01" required>
                        </div>
                        <div>
                            <label for="start">Partida:</label>
                            <input type="text" name="partida" id="start" placeholder=""/>
                        </div>
                        <div> 
                            <label for="end">Chegada:</label>
                            <input type="text" name="chegada" id="end" placeholder=""/>
                        </div>
                        <div class="places-container">
                            <label for="pontos">Pontos de Interesse:</label>
                            <select disabled name="pontos" id="placeType">
                                <option value="gas_station">Postos de Gasolina</option>
                            </select>
                        </div>
                        
                        <div class="btn">
                        <?php if ($loggedInClass === 'logged-in'): ?>
                            <button type="button" class="calculateBtn" onclick="calculateRoute()">Traçar Rota</button>
                            <button type="button" class="cadastroCalc" onclick="calcularCombustivel()">Calcular</button>
                            <button type="submit" class="saveRoute" id="salvaRota" onclick="salvaRota()">Salvar rota</button>
                        <?php else: ?>
                            <a href="cadastro/cadastro.php" class="calculateBtn">Traçar Rota</a>
                            <a href="cadastro/cadastro.php" class="cadastroCalc">Calcular</a>
                        <?php endif; ?>
                        </div>  
                </div>
                        <div>
                            <?php if ($loggedInClass === 'logged-in'): ?>
                                <input hidden type="text" name="login" id="login" value="<?php echo isset($_SESSION['login']) ? htmlspecialchars($_SESSION['login']) : '' ;?>"/>
                                <input hidden type="text" name="cpf" id="cpf" value="<?php echo isset($_SESSION['cpf']) ? htmlspecialchars($_SESSION['cpf']) : ''; ?>"/>
                                <input hidden type="text" name="consumo" id="consumo">
                                <input hidden type="text" name="custo" id="gasto">
                                <input hidden type="text" name="tempoEstimado" id="tempo">
                            <?php else: ?>
                                <input hidden type="text" name="cpf" value="Sem CPF logado.">
                            <?php endif; ?>
                        </div>
            </form>
            <div id="erro" style="display:none; color:red;">Ocorreu um erro ao traçar a rota. Verifique os endereços e tente novamente.</div>
            <div id="result"></div>
            <div id="resultado"></div>
            <div id="mensagem"></div>

        </div>
        <section class="containerTexto4" id="containerTexto">
            <h2>Como economizar o combustível?</h2>
            <h4>Aqui estão algumas dicas para economizar o combustível:</h4>
            <ul>
                <li>Respeite o momento da troca de marchas;</li>
                <li>Evite levar excesso de peso;</li>
                <li>Faça o alinhamento e o balanceamento;</li>
                <li>Não acelere com o veículo parado no farol;</li>
                <li>Evite deixar o veículo no ponto morto em descidas;</li>
                <li>Faça regulamente a calibração dos pneus;</li>
                <li>Faça a manutenção preventiva.</li>
            </ul>
        </section>
        <button onclick="voltarAoTopo()" id="btnTopo"><img class="setaTopo" src="img/seta-topo-removebg-preview.png" alt="setaTopo"></button>
    </main>
        <footer>
            <div class="containerFooter">
                <section class="footerContato"> 
                    <h4 class="textoFooter">Contato</h4>
                    <ul>
                        <li class="nomeContato">Vitória Rocha</li>
                        <li>(21) 98035-3819</li>
                    </ul>
                    <ul>
                        <li class="nomeContato">Daniel Nicola</li>
                        <li>(21) 99479-1703</li>
                    </ul>
                    <ul>
                        <li class="nomeContato">João Victor</li>
                        <li>(21) 97457-8229</li>
                    </ul>
                </section>
                <section class="footerSobreNos">
                    <h4 class="textoFooter">Sobre Nós</h4>
                    <p>Bem-vindo ao EcoKm! Nossa plataforma foi criada para facilitar o cálculo do consumo de combustível do seu veículo, 
                        seja para uma viagem, para o dia a dia ou deslocamentos de trabalho. Entendemos que o gasto com combustível é uma 
                        preocupação importante, e queremos ajudar você a tomar decisões mais conscientes e informadas.</p>
                    <p>Com o EcoKm, você pode calcular o consumo para qualquer distância, promovendo economia e eficiência em cada 
                        quilômetro percorrido. Nosso objetivo é contribuir para escolhas mais sustentáveis e econômicas no seu dia a 
                        dia, utilizando a tecnologia para otimizar o uso de seu veículo.</p>
                </section>
            </div>    
        </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC3NZISEAjOEfUocgQXVpigSVriLgwKpCI&libraries=places&callback=initMap" async defer></script>
</body>
</html>
