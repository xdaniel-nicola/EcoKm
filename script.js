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
        
        document.getElementById('resultado').innerText = `O consumo aproximado será de: ${consumo} litros e o custo será em torno de: R$${custo}.`;
        
        document.getElementById('consumo').value = consumo;
        document.getElementById('gasto').value = custo;
        setTimeout(function() {
            resultado.style.display = 'none';
        }, 10000);
    } else {
        document.getElementById('resultado').innerText = "Por favor, preencha todos os campos.";
        setTimeout(function() {
            resultado.style.display = 'none';
        }, 2000);
    }
}

document.getElementById("form-carro").addEventListener("submit", function (event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action , {
        method: "POST",
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            if (data === "sucesso") {
                document.getElementById("mensagem").textContent = "Rota salva com sucesso!";
                document.getElementById("mensagem").style.display = "block";
                setTimeout(function() {
                    mensagem.style.display = 'none';
                }, 2000);
                form.reset();
            } else {
                document.getElementById("mensagem").textContent = "Ocorreu um erro. Tente novamente.";
            }
        })
        .catch(error => {
            console.error("Erro", error);
            document.getElementById("mensagem").textContent = "Erro ao enviar o formulário."
        });
    });   

window.onload = carregarModelosDeCarros;
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
let currentIndex = 0;
const slides = document.querySelectorAll('.slide');

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
}

function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
}

setInterval(nextSlide, 3000); 

document.addEventListener('DOMContentLoaded', () => {
    showSlide(currentIndex);
});
