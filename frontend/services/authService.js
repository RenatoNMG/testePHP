document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", async (e) => {
        e.preventDefault(); // não recarrega a página

        // Pega os dados do formulário
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        try {
            // Envia para a API
            const response = await fetch("http://localhost/testePHP/api/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                // Salva token no localStorage
                localStorage.setItem("authToken", result.token);

                // Redireciona para a página vagas
                window.location.href = "vagas.html";
            } else {
                alert(result.message || "Erro ao fazer login!");
            }
        } catch (error) {
            console.error("Erro na requisição:", error);
            alert("Erro de conexão com o servidor.");
        }
    });
});
