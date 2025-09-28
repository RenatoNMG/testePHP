document.addEventListener("DOMContentLoaded", () => {
    const jobsList = document.getElementById("jobs-list");
    const candidatoId = localStorage.getItem('candidato_id');

    if (!candidatoId) {
        alert("Você precisa estar logado para ver suas vagas.");
        return;
    }

    const apiURL = `http://localhost:8080/api/get-vagas-por-criador.php?id_criador=${candidatoId}`;

    fetch(apiURL)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                jobsList.innerHTML = ""; // limpa o conteúdo inicial

                data.data.forEach(vaga => {
                    const vagaCard = document.createElement("div");
                    vagaCard.classList.add("job-card");

                    // Inclui o status no card
                    vagaCard.innerHTML = `
                        <div class="job-title">${vaga.titulo}</div>
                        <div class="job-desc">${vaga.descricao}</div>
                        <div class="job-type">${vaga.tipo}</div>
                        <div class="job-status">Status: ${vaga.status}</div>
                        <div class="job-actions">
                            <button class="edit-btn" onclick="editarVaga(${vaga.id})">Editar</button>
                            <button class="delete-btn" onclick="apagarVaga(${vaga.id})">Apagar</button>
                        </div>
                    `;

                    jobsList.appendChild(vagaCard);
                });
            } else {
                jobsList.innerHTML = "<p>Nenhuma vaga encontrada.</p>";
            }
        })
        .catch(err => {
            console.error("Erro ao carregar vagas:", err);
            jobsList.innerHTML = "<p>Erro ao carregar vagas. Verifique o console.</p>";
        });
});

// Função para editar
function editarVaga(id) {
    window.location.href = `../pages/vagas-edit.html?id=${id}`;
}

// Função para apagar
function apagarVaga(id) {
    if (confirm("Deseja realmente apagar esta vaga?")) {
        fetch(`http://localhost:8080/api/delete-vaga.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: id }) // envia o ID no corpo
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
            else alert("Erro ao apagar a vaga: " + data.message);
        })
        .catch(err => console.error("Erro ao apagar vaga:", err));
    }
}
