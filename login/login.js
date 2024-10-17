document.getElementById('toggleTheme').addEventListener('click', function (e) {
    e.preventDefault();
    
    const body = document.body;
    const themeIcon = document.getElementById('themeIcon');
    
    // Alterna entre modo claro e escuro
    if (body.classList.contains('light-mode')) {
        body.classList.remove('light-mode'); // Remove a classe de modo claro
        themeIcon.textContent = '🌙'; 
    } else {
        body.classList.add('light-mode'); // Adiciona a classe de modo claro
        themeIcon.textContent = '☀️'; 
    }
});
