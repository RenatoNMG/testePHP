const tipoSelect = document.querySelector("select"); // seu select de tipos

async function carregarVagas(page = 1) {
    try {
        const tipo = tipoSelect.value; // pega o tipo selecionado
        const url = new URL("http://localhost:8080/api/vagas.php");
        url.searchParams.append("page", page);
        url.searchParams.append("limit", 20);
        if (tipo) url.searchParams.append("tipo", tipo); // adiciona filtro se selecionado

        const response = await fetch(url);
        const result = await response.json();

        if (!result.success) {
            console.error("Erro da API:", result.error || "Erro desconhecido");
            return;
        }

        const jobsList = document.querySelector(".jobs-list");
        jobsList.innerHTML = ""; // limpa antes de renderizar

        result.data.forEach(vaga => {
            const card = document.createElement("div");
            card.classList.add("job-card");

            card.innerHTML = `
                <div class="job-title">${vaga.titulo}</div>
                <div class="job-desc">${vaga.descricao}</div>
                <div class="job-type">${vaga.tipo}</div>
                <button onclick="inscrever(${vaga.id})">Inscrever-se</button>
            `;

            jobsList.appendChild(card);
        });

        renderPaginacao(result.page, result.pages);

    } catch (error) {
        console.error("Erro ao carregar vagas:", error);
    }
}

function renderPaginacao(paginaAtual, totalPaginas) {
    const container = document.querySelector(".pagination");
    if (!container) return;

    container.innerHTML = "";

    for (let i = 1; i <= totalPaginas; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.disabled = i === paginaAtual;
        btn.onclick = () => carregarVagas(i);
        container.appendChild(btn);
    }
}

function inscrever(vagaId) {
    alert(`Você se inscreveu na vaga ID: ${vagaId}`);
}

// Recarrega a lista ao mudar o filtro
tipoSelect.addEventListener("change", () => carregarVagas(1));

// Inicializa a lista
document.addEventListener("DOMContentLoaded", () => {
    carregarVagas();
});
