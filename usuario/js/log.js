document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form[role='search']");

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const searchQuery = form.querySelector("input[name='busca']").value;

        fetchLogs(searchQuery);
    });
    
    fetchLogs();
});

function fetchLogs(searchQuery = "") {
    fetch("fetch_logs.php", {
        method: "POST",
        headers: {
            "Content-Type":"application/x-www-form-urlencoded",
        },
        body: `busca=${encodeURIComponent(searchQuery)}`,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error (`Erro: ${response.statuts}`);
            }
            return response.json();
        })
        .then((data) => {
            const tableBody = document.querySelector("#logTable tbody");
            tableBody.innerHTML = "";

            if(data.error) {
                tableBody.innerHTML = `<tr><td colspan="4">${data.error}</td></tr>`;
                return;
            }

            if (data.length === 0) {
                tableBody.innerHTML = `<tr<td colspan="4">Nenhum log encontrado.</td></tr>`;
                return;
            }
            data.forEach((log) => {
                const row = document.createElement("tr");
                row.innerHTML =`
                <td>${log.idLog}</td>
                <td>${log.usuario}</td>
                <td>${log.acao}</td>
                <td>${log.dataLog}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch((error) => {
            console.error("Erro ao buscar os dados: ", error);
            const tableBody = document.querySelector("#logTable tbody");
            tableBody.innerHTML= `<tr><td colspan="4"> Erro ao carregar dados: ${error.message}</td></tr>`;
        });
};



    
    
    
    
//     fetch("fetch_logs.php")
//         .then(response => response.json())
//         .then(data => {
//             const tableBody = document.querySelector("#logTable tbody");
//             data.forEach(log => {
//                 const row = document.createElement("tr");
//                 row.innerHTML = `
//                     <td>${log.idLog}</td>
//                     <td>${log.usuario}</td>
//                     <td>${log.acao}</td>
//                     <td>${log.dataLog}</td>
//                 `;
//                 tableBody.appendChild(row);
//             });
//         })
//         .catch(error => console.error("Error fetching logs:", error));
// });

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