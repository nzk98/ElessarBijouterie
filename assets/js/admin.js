function deleteCreationImage(creationId, imagePath, button) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        fetch('index.php?page=AdminFormCreation&action=deleteImage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                creation_id: creationId,
                image_path: imagePath
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer l'élément image-item du DOM
                button.closest('.image-item').remove();
            } else {
                alert('Erreur lors de la suppression de l\'image');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression de l\'image');
        });
    }
}

// Fonction pour supprimer une image d'article
function deleteArticleImage(articleId, imagePath, button) {
    if (confirm('Voulez-vous vraiment supprimer cette image ?')) {
        fetch('index.php?page=AdminFormArticle&action=deleteImage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                article_id: articleId,
                image_path: imagePath
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.closest('.image-item').remove();
            } else {
                alert('Erreur lors de la suppression de l\'image');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression de l\'image');
        });
    }
}

// Fonction de validation du mot de passe selon le pattern demandé
function validatePassword(password) {
    const length = password.length >= 8;
    const uppercase = (password.match(/[A-Z]/g) || []).length >= 2;
    const number = /[0-9]/.test(password);
    const special = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    
    return { length, uppercase, number, special };
}

// Fonction de mise à jour des indicateurs de validation du mot de passe
function updatePasswordRequirements(password, containerId = 'password-requirements') {
    const requirements = validatePassword(password);
    const container = document.getElementById(containerId);
    
    if (container) {
        const lengthCheck = container.querySelector('#length-check');
        const uppercaseCheck = container.querySelector('#uppercase-check');
        const numberCheck = container.querySelector('#number-check');
        const specialCheck = container.querySelector('#special-check');
        
        if (lengthCheck) {
            lengthCheck.innerHTML = requirements.length ? '✅ Au moins 8 caractères' : '❌ Au moins 8 caractères';
        }
        if (uppercaseCheck) {
            uppercaseCheck.innerHTML = requirements.uppercase ? '✅ Au moins 2 majuscules' : '❌ Au moins 2 majuscules';
        }
        if (numberCheck) {
            numberCheck.innerHTML = requirements.number ? '✅ Au moins 1 chiffre' : '❌ Au moins 1 chiffre';
        }
        if (specialCheck) {
            specialCheck.innerHTML = requirements.special ? '✅ Au moins 1 caractère spécial' : '❌ Au moins 1 caractère spécial';
        }
    }
}

// Fonction de vérification de correspondance des mots de passe
function checkPasswordMatch(passwordId, confirmId, matchId) {
    const password = document.getElementById(passwordId);
    const passwordConfirm = document.getElementById(confirmId);
    const matchElement = document.getElementById(matchId);
    
    if (password && passwordConfirm && matchElement) {
        const match = password.value === passwordConfirm.value && password.value !== '';
        matchElement.innerHTML = match ? '✅ Les mots de passe correspondent' : '❌ Les mots de passe ne correspondent pas';
        return match;
    }
    return false;
}

// Initialisation de la validation du mot de passe pour le formulaire d'inscription admin
function initAdminRegisterValidation() {
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const form = document.getElementById('admin-register-form');
    
    if (password && passwordConfirm && form) {
        password.addEventListener('input', function() {
            updatePasswordRequirements(this.value);
            checkPasswordMatch('password', 'password_confirm', 'password-match');
        });
        
        passwordConfirm.addEventListener('input', function() {
            checkPasswordMatch('password', 'password_confirm', 'password-match');
        });
        
        form.addEventListener('submit', function(e) {
            const requirements = validatePassword(password.value);
            const allValid = requirements.length && requirements.uppercase && requirements.number && requirements.special;
            const passwordsMatch = checkPasswordMatch('password', 'password_confirm', 'password-match');
            
            if (!allValid) {
                e.preventDefault();
                alert('Le mot de passe ne respecte pas tous les critères de sécurité.');
                return false;
            }
            
            if (!passwordsMatch) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
                return false;
            }
        });
    }
}

// Initialisation de la validation du mot de passe pour le formulaire d'édition admin
function initAdminEditValidation() {
    const showPasswordBtn = document.getElementById('show-password-fields');
    const password = document.getElementById('password');
    const form = document.getElementById('edit-admin-form');
    
    if (showPasswordBtn) {
        showPasswordBtn.addEventListener('click', function() {
            var fields = document.getElementById('password-fields');
            var changeMdp = document.getElementById('change_mdp');
            var requirements = document.getElementById('password-requirements');
            
            if (fields.classList.contains('show')) {
                fields.classList.remove('show');
                changeMdp.value = '0';
                if (requirements) requirements.classList.remove('show');
            } else {
                fields.classList.add('show');
                changeMdp.value = '1';
                if (requirements) requirements.classList.add('show');
            }
            
            // Réinitialiser les champs de mot de passe
            document.getElementById('current_password').value = '';
            document.getElementById('password').value = '';
            document.getElementById('current-password-error').classList.remove('show');
            // Réinitialiser les indicateurs
            updatePasswordRequirements('');
        });
    }
    
    if (password) {
        password.addEventListener('input', function() {
            updatePasswordRequirements(this.value);
        });
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            var changeMdp = document.getElementById('change_mdp').value;
            var currentPassword = document.getElementById('current_password').value;
            var newPassword = document.getElementById('password').value;
            
            if (changeMdp === '1') {
                if (!currentPassword) {
                    e.preventDefault();
                    alert('Veuillez entrer votre mot de passe actuel.');
                    return false;
                }
                if (!newPassword) {
                    e.preventDefault();
                    alert('Veuillez entrer le nouveau mot de passe.');
                    return false;
                }
                
                // Validation du pattern du nouveau mot de passe
                const requirements = validatePassword(newPassword);
                const allValid = requirements.length && requirements.uppercase && requirements.number && requirements.special;
                
                if (!allValid) {
                    e.preventDefault();
                    alert('Le nouveau mot de passe ne respecte pas tous les critères de sécurité.');
                    return false;
                }
            }
        });
    }
}

// Initialisation automatique quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    initAdminRegisterValidation();
    initAdminEditValidation();
}); 