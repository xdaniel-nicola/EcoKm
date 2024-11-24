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

document.addEventListener('DOMContentLoaded', function () {
    const cancelarBtn = document.getElementById('cancelarBtn');
    const modal = document.getElementById('modalCancelar');
    const closeModal = document.getElementById('closeModal'); 
    const simCancelarBtn = document.getElementById('simCancelarBtn'); 
    const voltarBtn = document.getElementById('voltarBtn');  
    const formCancelamento = document.getElementById('formCancelamento'); 

    cancelarBtn.addEventListener('click', function () {
        const idPlano = this.getAttribute('data-id');  
        document.getElementById('id_plano_modal').value = idPlano;
        modal.style.display = 'block';
    });

    closeModal.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    voltarBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    simCancelarBtn.addEventListener('click', function () {
        formCancelamento.submit(); 
    });

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
