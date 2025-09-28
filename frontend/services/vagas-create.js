// Seleciona o formulário
const vagaForm = document.getElementById('vaga-form');

// URL da API de cadastro de vagas
const apiURL = 'http://localhost:8080/api/create-vaga.php';

vagaForm.addEventListener('submit', (event) => {
    event.preventDefault();

    
    const titulo = document.querySelector('input[name="titulo"]').value.trim();
    const descricao = document.querySelector('textarea[name="descricao"]').value.trim();
    const tipo = document.querySelector('select[name="tipo"]').value;
    const status = document.querySelector('select[name="status"]').value;


    if (!titulo || !descricao || !tipo || !status) {
        alert('Preencha todos os campos corretamente!');
        return;
    }

    // Cria o objeto para enviar
    const vagaData = { titulo, descricao, tipo, status };

    // Envia via fetch
    fetch(apiURL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(vagaData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Vaga criada com sucesso!');
        
            // vagaForm.reset();
            window.location.href = 'vagas.html'; // caso queira redirecionar
        } else if (data.error) {
            alert('Erro: ' + data.error);
        } else {
            alert('Erro desconhecido ao criar vaga.');
        }
    })
    .catch(error => {
        console.error('Erro na requisição fetch:', error);
        alert('Erro ao conectar com a API. Verifique o console.');
    });
});
