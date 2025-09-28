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

    //Verifique se os dados estão sendo coletados corretamente
    console.log('Dados do formulário coletados:', loginData);

    fetch(apiURL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(loginData)
    })
    .then(response => {
        //Verifique a URL e o status da resposta
        console.log('Resposta da API recebida. Status:', response.status);
        if (!response.ok) {
            throw new Error(`Erro na API: ${response.status} ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
    console.log('Dados da API:', data);
    
    if (data.success) {
        alert('Login bem-sucedido! Bem-vindo(a)!');
        
        
        localStorage.setItem('token', data.token);
        localStorage.setItem('candidato_id', data.id); 
        localStorage.setItem('candidato_nome', data.nome);
        
        window.location.href = '../pages/vagas.html';
    } else {
        alert('Erro no login: ' + data.message);
    }
})

});


const passwordInput = document.querySelector('input[name="senha"]');
const eyeIcon = document.querySelector('.password-wrapper svg');


eyeIcon.addEventListener('click', () => {
   
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

});