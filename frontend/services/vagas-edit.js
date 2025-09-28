document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("vaga-form");

    // Pega o ID da vaga da URL
    const urlParams = new URLSearchParams(window.location.search);
    const vagaId = urlParams.get("id");

    if (!vagaId) {
        alert("ID da vaga não encontrado!");
        return;
    }

    const apiGetURL = `http://localhost:8080/api/get-vaga.php?id=${vagaId}`;
    const apiUpdateURL = `http://localhost:8080/api/update-vaga.php`;

    // Função para preencher o formulário
    fetch(apiGetURL)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data) {
                const vaga = data.data;
                form.titulo.value = vaga.titulo;
                form.descricao.value = vaga.descricao;
                form.tipo.value = vaga.tipo;
                form.status.value = vaga.status;
            } else {
                alert("Vaga não encontrada.");
            }
        })
        .catch(err => {
            console.error("Erro ao buscar vaga:", err);
            alert("Erro ao carregar vaga. Veja o console.");
        });

    // Enviar dados atualizados
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const updatedData = {
            id: parseInt(vagaId),
            titulo: form.titulo.value.trim(),
            descricao: form.descricao.value.trim(),
            tipo: form.tipo.value,
            status: form.status.value,
            criado_por: parseInt(localStorage.getItem('candidato_id'))
        };


        fetch(apiUpdateURL, {
            method: "POST", // ou PUT, dependendo da sua API
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(updatedData)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Vaga atualizada com sucesso!");
                    window.location.href = "minhas-vagas.html";
                } else {
                    alert("Erro ao atualizar vaga: " + data.message);
                }
            })
            .catch(err => {
                console.error("Erro ao atualizar vaga:", err);
                alert("Erro ao atualizar vaga. Veja o console.");
            });
    });
});
