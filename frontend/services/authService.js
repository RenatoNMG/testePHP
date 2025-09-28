const loginForm = document.getElementById('login-form');
const apiURL = 'http://localhost:8080/api/login.php';

loginForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const email = document.querySelector('input[name="email"]').value;
    const senha = document.querySelector('input[name="senha"]').value;

    const loginData = {
        email: email,
        senha: senha
    };

    // Teste 1: Verifique se os dados estão sendo coletados corretamente
    console.log('Dados do formulário coletados:', loginData);

    fetch(apiURL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(loginData)
    })
    .then(response => {
        // Teste 2: Verifique a URL e o status da resposta
        console.log('Resposta da API recebida. Status:', response.status);
        if (!response.ok) {
            throw new Error(`Erro na API: ${response.status} ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        // Teste 3: Verifique o conteúdo da resposta da API
        console.log('Dados da API:', data);
        
        if (data.success) {
            alert('Login bem-sucedido! Bem-vindo(a)!');
            window.location.href = 'vagas.html';
        } else {
            alert('Erro no login: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro na requisição fetch:', error);
        alert('Erro ao tentar conectar com o servidor. Verifique o console.');
    });
});

// Seleciona o campo de senha e o ícone de olho
const passwordInput = document.querySelector('input[name="senha"]');
const eyeIcon = document.querySelector('.password-wrapper svg');

// logica para mostrar a senha
eyeIcon.addEventListener('click', () => {
   
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

});