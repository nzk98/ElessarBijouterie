// Gestion de la bascule entre connexion et inscription
document.getElementById('show-register').addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('login-section').style.display = 'none';
    document.getElementById('register-section').style.display = 'block';
});

document.getElementById('show-login').addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('register-section').style.display = 'none';
    document.getElementById('login-section').style.display = 'block';
});

// Validation du formulaire d'inscription
const registerForm = document.getElementById('register-form');
registerForm.addEventListener('submit', (e) => {
    const password = document.getElementById('register-password').value;
    const confirmPassword = document.getElementById('register-password-confirm').value;

    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas.');
        return false;
    }
    // Sinon, le formulaire s'envoie normalement
});

