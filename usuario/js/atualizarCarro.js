document.getElementById('form').addEventListener('submit', function (e) {
    const modelo = document.getElementById('modelo').value.trim();
    const marca = document.getElementById('marca').value.trim();
    const motor = document.getElementById('motor').value;

    if (!modelo || !marca || motor === '0') {
        alert('Por favor, preencha todos os campos corretamente.');
        e.preventDefault();
    }
});