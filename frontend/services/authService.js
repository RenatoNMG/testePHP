const loginForm = document.getElementById('login-form');
const apiURL = 'http://localhost:8080/api/login.php';

loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const email = document.querySelector('input[name="email"]').value.trim();
    const senha = document.querySelector('input[name="senha"]').value.trim();

    if (!email || !senha) {
        alert("Email e senha são obrigatórios.");
        return;
    }

    const loginData = { email, senha };

    try {
        const response = await fetch(apiURL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(loginData)
        });

        // Lê o JSON da resposta, mesmo se o status for 401
        const data = await response.json();
        console.log('Resposta da API:', response.status, data);

        if (response.ok && data.success) {
            alert('Login bem-sucedido! Bem-vindo(a)!');
            localStorage.setItem('token', data.token);
            localStorage.setItem('candidato_id', data.id);
            window.location.href = '../pages/vagas.html';
        } else {
            // Mostra a mensagem do backend ou padrão
            alert(data.message || 'Email ou senha incorretos.');
        }

    } catch (error) {
        console.error('Erro na requisição:', error);
        alert('Erro ao se conectar com a API.');
    }
});

// Toggle “ver senha”
const passwordInput = document.querySelector('input[name="senha"]');
const eyeIcon = document.querySelector('.password-wrapper svg');

eyeIcon.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
});
