document.getElementById("btnApagar").addEventListener("click", async () => {
    const id = localStorage.getItem("candidato_id");

    if (!id) {
        alert("Nenhum ID encontrado no localStorage!");
        return;
    }

    try {
        const response = await fetch("http://localhost:8080/api/delete-candidato.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_candidato: parseInt(id) })
        });

        const result = await response.json();
        console.log(result);

        // ⚠️ Usar as chaves corretas do PHP
        if (result.success) {
            alert(result.message); // message, não mensagem
            localStorage.clear();
            window.location.href = '../index.html';
        } else {
            alert(result.message);
        }

    } catch (error) {
        console.error("Erro na requisição:", error);
        alert("Erro ao se conectar com a API.");
    }
});
