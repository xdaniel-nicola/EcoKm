// Simulação de banco de dados de carros (pode ser substituído por uma chamada de API)
const carDatabase = [
    { modelo: '1.0', eficiencia: 15.3 },
    { modelo: '1.3', eficiencia: 14.2 },
    { modelo: '1.4', eficiencia: 13.6 },
    { modelo: '1.5', eficiencia: 12.6},
    { modelo: '1.6', eficiencia: 11.6},
    { modelo: '1.8', eficiencia: 10.7 },
    { modelo: '2.0', eficiencia: 9.8 },
];

function carregarModelosDeCarros() {
    const selectModeloCarro = document.getElementById('modelo-carro');
    carDatabase.forEach(carro => {
        const opcao = document.createElement('option');
        opcao.value = carro.eficiencia;
        opcao.text = carro.modelo;
        selectModeloCarro.add(opcao);
    });
}

function calcularCombustivel() {
    const eficienciaModelo = parseFloat(document.getElementById('modelo-carro').value);
    const eficienciaCombustivel = parseFloat(document.getElementById('tipo-combustivel').value);
    const distancia = parseFloat(document.getElementById('distancia').value);
    const precoCombustivel = parseFloat(document.getElementById('preco-combustivel').value);

    if (distancia && precoCombustivel) {
        const eficienciaCombinada = (eficienciaModelo * eficienciaCombustivel) / 10;
        const consumo = (distancia / eficienciaCombinada).toFixed(2);
        const custo = (consumo * precoCombustivel).toFixed(2);
        
        document.getElementById('resultado').innerText = `O consumo aproximado será de: ${consumo} litros e o custo será algo em torno de: R$${custo}.`;
    } else {
        document.getElementById('resultado').innerText = "Por favor, preencha todos os campos.";
    }
}

function logar() {
    const usuario = document.getElementById('usuario').value;

    if (usuario) {
        document.getElementById('usuario-logado').innerText = `Bem-vindo, ${usuario}!`;
        document.getElementById('login-form').style.display = 'none';
    } else {
        alert("Por favor, insira um nome de usuário.");
    }
}

// Carrega os modelos de carro quando a página é carregada
window.onload = carregarModelosDeCarros;
// Função para alternar o tema
const toggleSwitch = document.getElementById('toggle-switch');
const body = document.body;

toggleSwitch.addEventListener('change', () => {
    if (toggleSwitch.checked) {
        body.classList.add('light-mode');
        body.classList.remove('dark-mode');
    } else {
        body.classList.add('dark-mode');
        body.classList.remove('light-mode');
    }
});

// Carregar o tema correto ao carregar a página
window.addEventListener('load', () => {
    const isLightMode = localStorage.getItem('theme') === 'light';
    if (isLightMode) {
        toggleSwitch.checked = true;
        body.classList.add('light-mode');
    } else {
        body.classList.add('dark-mode');
    }
});

toggleSwitch.addEventListener('change', () => {
    const theme = toggleSwitch.checked ? 'light' : 'dark';
    localStorage.setItem('theme', theme);
});

function voltarAoTopo() {
    window.scrollTo({top: 0, behavior: 'smooth'})
}

window.onscroll = function() {
    mostrarBotao();
}

function mostrarBotao() {
    const botao = document.getElementById("btnTopo");
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        botao.style.display = "block";
    } else {
        botao.style.display = "none";
    }
}