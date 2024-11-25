document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript carregado');
    const form = document.getElementById("form");
    const campos = document.querySelectorAll('.conteudo input, .conteudo select');
    const spans = document.querySelectorAll('.span-required');
    const selectElement = document.getElementById('sexo');
    const toggleThemeButton = document.getElementById('toggleTheme');

    const marcaRegex = /^[a-zA-Z]{2,}$/;
    const modeloRegex = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]{2,}$/;

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
        event.preventDefault(); 
        validador(); 
    });

    function validador() {
        const isModeloValid = modeloValidate();
        const isMarcaValid = marcaValidate();
        const isMotorValid = motorValidate();

        console.log("Nome válido: ", isModeloValid)
        console.log("Email válido: ", isMarcaValid);
        console.log("CPF válido: ", isMotorValid);

        if (isModeloValid && isMarcaValid && isMotorValid) {
            form.submit(); 
            return true;
        } else {
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

    campos[0].addEventListener('input', modeloValidate);
    campos[1].addEventListener('input', marcaValidate);
    campos[2].addEventListener('input', motorValidate);
    
    function modeloValidate() {
        if (!modeloRegex.test(campos[0].value.trim())) {
            setError(0);
            return false;
        } else {
            removeError(0);
            return true;
        }
    }

    function marcaValidate() {
        if (!marcaRegex.test(campos[1].value.trim())) {
            setError(1);
            return false;
        } else {
            removeError(1);
            return true;
        }
    }
    
    function motorValidate() {
        const selectElement = document.getElementById('motor');
        if (!selectElement) {
            console.error("Elemento 'motor' não encontrado");
            return false;
        }
        if (selectElement.value === "0") {
            setError(2);
            return false;
        } else {
            removeError(2);
            return true;
        }
    }
})
