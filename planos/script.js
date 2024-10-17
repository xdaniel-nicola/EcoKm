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