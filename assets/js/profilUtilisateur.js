document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.querySelector('.profile-form');
    const telephoneField = document.getElementById('telephone');

    // Validation du téléphone
    function validateTelephone(telephone) {
        if (telephone) {
            // Supprimer tous les caractères non numériques
            const numbers = telephone.replace(/[^0-9]/g, '');
            
            if (numbers.length !== 10) {
                return "Le numéro de téléphone doit contenir 10 chiffres (ex: 06 12 34 56 78)";
            }
            
            // Vérifier que ça commence par 0
            if (!numbers.startsWith('0')) {
                return "Le numéro de téléphone doit commencer par 0";
            }
        }
        return null;
    }

    // Fonction pour afficher une erreur
    function showError(field, message) {
        const formGroup = field.closest('.form-group');
        formGroup.classList.add('has-error');
        
        // Supprimer l'ancien message d'erreur s'il existe
        const existingError = formGroup.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        // Créer et afficher le nouveau message d'erreur
        const errorSpan = document.createElement('span');
        errorSpan.className = 'error-message';
        errorSpan.textContent = message;
        formGroup.appendChild(errorSpan);
    }

    // Fonction pour supprimer une erreur
    function clearError(field) {
        const formGroup = field.closest('.form-group');
        formGroup.classList.remove('has-error');
        
        const errorSpan = formGroup.querySelector('.error-message');
        if (errorSpan) {
            errorSpan.remove();
        }
    }

    // Validation du téléphone en temps réel
    if (telephoneField) {
        telephoneField.addEventListener('blur', () => {
            const error = validateTelephone(telephoneField.value);
            if (error) {
                showError(telephoneField, error);
            } else {
                clearError(telephoneField);
            }
        });

        // Nettoyer l'erreur lors de la saisie
        telephoneField.addEventListener('input', () => {
            clearError(telephoneField);
        });
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

    // Initialisation de la validation du mot de passe pour le profil utilisateur
    function initUserProfileValidation() {
        const password = document.getElementById('password');
        const form = document.querySelector('.profile-form');
        
        if (password) {
            password.addEventListener('input', function() {
                const requirements = document.getElementById('password-requirements');
                if (this.value.length > 0) {
                    requirements.classList.add('show');
                    updatePasswordRequirements(this.value);
                } else {
                    requirements.classList.remove('show');
                }
            });
        }
        
        if (form) {
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password');
                
                if (password.value.length > 0) {
                    // Validation du pattern du nouveau mot de passe
                    const requirements = validatePassword(password.value);
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
    initUserProfileValidation();
}); 