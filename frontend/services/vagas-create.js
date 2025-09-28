// Seleciona o formulário
const vagaForm = document.getElementById('vaga-form');
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

    // Pega o ID do candidato logado
    const candidatoId = localStorage.getItem('candidato_id');

    // Cria o objeto para enviar, incluindo o ID do criador
    const vagaData = { 
        titulo, 
        descricao, 
        tipo, 
        status,
        criado_por: candidatoId // <--- aqui enviamos o ID do candidato
    };

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
            window.location.href = 'vagas.html';
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
