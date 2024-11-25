document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript carregado');
    const form = document.getElementById("form");
    const campos = document.querySelectorAll('.container input');
    const spans = document.querySelectorAll('.span-required');

    const numCartaoRegex = /^[0-9]{4}\ [0-9]{4}\ [0-9]{4}\ [0-9]{4}$/;
    const validadeRegex = /^(0?[1-9]|[12][0-9]|3[01])\/(0?[1-9]|1[012])/;
    const CVVRegex = /^[0-9]{3}$/;
 

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
form.addEventListener('submit', function(event) {
    event.preventDefault(); // Evita o envio padrão do formulário
    validador(); // Função para validar os campos antes de enviar
});

function validador() {
    const isNumCartaoValid = numCartaoValidate();
    const isCVVValid = CVVValidate();
    const isValidadeValid = validadeValidate();

    console.log("Nome válido: ", isNumCartaoValid)
    console.log("Email válido: ", isCVVValid);
    console.log("Data válida: ", isValidadeValid);

    if (isNumCartaoValid && isCVVValid && isValidadeValid) {
        form.submit(); // Envio do formulário se todos os campos forem válidos
        return true;
    } else {
        // alert('Por favor, preencha todos os campos corretamente.');
        return false;
    }
}

function setError(index) {
    campos[index].style.border = '2px solid #e63636';
    spans[index].style.display = 'block';
}

function removeError(index) {
    campos[index].style.border = '';
    spans[index].style.display = 'none';
}

// campos[0].addEventListener('input', numCartaoValidate);
// campos[1].addEventListener('input', CVVValidate);
// campos[2].addEventListener('input', validadeValidate);


function numCartaoValidate() {
    if (!numCartaoRegex.test(campos[0].value.trim())) {
        setError(0);
        return false;
    } else {
        removeError(0);
        return true;
    }
}

function CVVValidate() {
    if (!CVVRegex.test(campos[1].value.trim())) {
        setError(1);
        return false;
    } else {
        removeError(1);
        return true;
    }
}

function validadeValidate() {
    if (!validadeRegex.test(campos[2].value.trim())) {
        setError(2);
        return false;
    } else {
        removeError(2);
        return true;
    }
}
});