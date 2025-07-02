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

// Fonction pour initialiser la validation du mot de passe sur un champ
function initPasswordValidation(passwordId, containerId = 'password-requirements') {
    const password = document.getElementById(passwordId);
    const container = document.getElementById(containerId);
    
    if (password && container) {
        password.addEventListener('input', function() {
            if (this.value.length > 0) {
                container.style.display = 'block';
                updatePasswordRequirements(this.value, containerId);
            } else {
                container.style.display = 'none';
            }
        });
    }
}

// Fonction pour valider un mot de passe lors de la soumission d'un formulaire
function validatePasswordOnSubmit(passwordId, formId) {
    const password = document.getElementById(passwordId);
    const form = document.getElementById(formId);
    
    if (password && form) {
        form.addEventListener('submit', function(e) {
            if (password.value.length > 0) {
                const requirements = validatePassword(password.value);
                const allValid = requirements.length && requirements.uppercase && requirements.number && requirements.special;
                
                if (!allValid) {
                    e.preventDefault();
                    alert('Le mot de passe ne respecte pas tous les critères de sécurité.');
                    return false;
                }
            }
        });
    }
}

// Fonction pour initialiser la validation complète (mot de passe + confirmation)
function initCompletePasswordValidation(passwordId, confirmId, matchId, formId) {
    const password = document.getElementById(passwordId);
    const passwordConfirm = document.getElementById(confirmId);
    const form = document.getElementById(formId);
    
    if (password && passwordConfirm && form) {
        password.addEventListener('input', function() {
            updatePasswordRequirements(this.value);
            checkPasswordMatch(passwordId, confirmId, matchId);
        });
        
        passwordConfirm.addEventListener('input', function() {
            checkPasswordMatch(passwordId, confirmId, matchId);
        });
        
        form.addEventListener('submit', function(e) {
            const requirements = validatePassword(password.value);
            const allValid = requirements.length && requirements.uppercase && requirements.number && requirements.special;
            const passwordsMatch = checkPasswordMatch(passwordId, confirmId, matchId);
            
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