
const tipoSelect = document.querySelector("#tipo-select");
const orderSelect = document.querySelector("#order-select");
const limitSelect = document.querySelector("#limit-select");
const jobsList = document.querySelector(".jobs-list");
const paginationContainer = document.querySelector(".pagination");

let currentPage = 1; // página inicial

// Função principal para carregar vagas
async function carregarVagas(page = 1) {
    try {
        const tipo = tipoSelect.value;
        const order = orderSelect.value;
        const limit = limitSelect.value;

        const response = await fetch("http://localhost:8080/api/vagas.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                tipo,
                order,
                limit,
                page
            })
        });

        const data = await response.json();

        if (data.success) {
            renderVagas(data.data);
            renderPagination(data.pagination);
        } else {
            jobsList.innerHTML = "<p>Nenhuma vaga encontrada.</p>";
        }

    } catch (error) {
        console.error("Erro ao carregar vagas:", error);
        jobsList.innerHTML = "<p>Erro ao carregar vagas.</p>";
    }
}

// Renderizar lista de vagas
function renderVagas(vagas) {
    jobsList.innerHTML = "";

    vagas.forEach(vaga => {
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
}

// Renderizar paginação
function renderPagination(pagination) {
    paginationContainer.innerHTML = "";

    for (let i = 1; i <= pagination.pages; i++) {
        const button = document.createElement("button");
        button.textContent = i;
        button.classList.add("page-btn");

        if (i === pagination.page) {
            button.disabled = true;
            button.classList.add("active");
        }

        button.addEventListener("click", () => {
            currentPage = i;
            carregarVagas(currentPage);
        });

        paginationContainer.appendChild(button);
    }
}

async function inscrever(vagaId) {
    try {

        const candidatoId = localStorage.getItem("candidato_id");

        if (!candidatoId) {
            alert("Nenhum candidato logado.");
            return;
        }

        const data = {
            idCandidato: candidatoId,
            idVaga: vagaId
        };


        const response = await fetch("http://localhost:8080/api/inscricao.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error("Erro ao inscrever-se na vaga");
        }

        const result = await response.json();
        alert(result.message || "Inscrição realizada com sucesso!");
    } catch (error) {
        console.error("Erro:", error);
        alert("Erro ao se inscrever. Tente novamente.");
    }
}


// Eventos dos filtros
tipoSelect.addEventListener("change", () => carregarVagas(1));
orderSelect.addEventListener("change", () => carregarVagas(1));
limitSelect.addEventListener("change", () => carregarVagas(1));

// Carregar primeira vez
carregarVagas();

