// cadastro.js
const cadastroForm = document.getElementById('cadastro-form');
const apiURL = 'http://localhost:8080/api/cadastro.php'; // URL da sua API de cadastro

cadastroForm.addEventListener('submit', (event) => {
    event.preventDefault();

    // Pega os valores do formulário
    const nome = document.querySelector('input[name="nome"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const senha = document.querySelector('input[name="senha"]').value;
    const confirmar_senha = document.querySelector('input[name="confirmar_senha"]').value;

    // Validação de campos vazios
    if (!nome || !email || !senha || !confirmar_senha) {
        alert('Por favor, preencha todos os campos.');
        return;
    }

    // NOVO: Validação para verificar se as senhas são iguais
    if (senha !== confirmar_senha) {
        alert('As senhas não coincidem. Por favor, tente novamente.');
        return; // Impede o restante da execução
    }

    // Cria o objeto a enviar (apenas a senha, não a confirmação)
    const cadastroData = { nome, email, senha };
    console.log('Dados enviados:', cadastroData); // debug

    // Envia para a API
    fetch(apiURL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(cadastroData)
    })
    .then(response => {
        if (!response.ok) throw new Error(`Erro na API: ${response.status}`);
        return response.json();
    })
    .then(data => {
        
        if (data.success && data.token) {
            localStorage.setItem('token', data.token);
            window.location.href = '../pages/vagas.html';
        } else {
            alert('Erro no cadastro: ' + (data.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Erro na requisição fetch:', error);
        alert('Não foi possível conectar à API. Verifique o console.');
    });
});

// Seleciona o campo de senha e o ícone de olho (seu código para a funcionalidade de mostrar a senha)
const passwordInput = document.querySelector('input[name="senha"]');
const eyeIcon = document.querySelector('.password-wrapper svg');

eyeIcon.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
});