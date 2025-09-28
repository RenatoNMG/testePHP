
// Verifica se existe token no localStorage
const token = localStorage.getItem('token');

if (!token) {

  window.location.href = '../index.html';
}
function sair() {
    localStorage.clear();
    window.location.href = '../index.html';
}


document.addEventListener('DOMContentLoaded', () => {

    const sairButton = document.getElementById('sair-button');

    if (sairButton) {
        sairButton.addEventListener('click', sair);
    }
});