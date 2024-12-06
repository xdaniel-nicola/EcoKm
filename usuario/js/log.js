document.addEventListener("DOMContentLoaded", () => {
    fetch("fetch_logs.php")
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector("#logTable tbody");
            data.forEach(log => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${log.idLog}</td>
                    <td>${log.usuario}</td>
                    <td>${log.acao}</td>
                    <td>${log.dataLog}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error("Error fetching logs:", error));
});

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