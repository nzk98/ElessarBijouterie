// Gestion de la bascule entre connexion et inscription
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');
    const loginSection = document.getElementById('login-section');
    const registerSection = document.getElementById('register-section');

    // Fonctions de validation
    const validators = {
        email: (email) => {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) return "L'email est obligatoire.";
            if (!emailRegex.test(email)) return "L'email n'est pas valide.";
            return null;
        },
        
        password: (password) => {
            if (!password) return "Le mot de passe est obligatoire.";
            if (password.length < 8) return "Le mot de passe doit contenir au moins 8 caractères.";
            
            // Validation de la complexité
            const uppercaseCount = (password.match(/[A-Z]/g) || []).length;
            const digitCount = (password.match(/[0-9]/g) || []).length;
            const specialCount = (password.match(/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g) || []).length;
            
            if (uppercaseCount < 2) return "Le mot de passe doit contenir au moins 2 majuscules.";
            if (digitCount < 1) return "Le mot de passe doit contenir au moins 1 chiffre.";
            if (specialCount < 1) return "Le mot de passe doit contenir au moins 1 caractère spécial.";
            
            return null;
        },
        
        name: (name, fieldName) => {
            if (!name) return `Le ${fieldName} est obligatoire.`;
            if (name.length < 2) return `Le ${fieldName} doit contenir au moins 2 caractères.`;
            return null;
        },
        
        civilite: (civilite) => {
            if (!civilite) return "La civilité est obligatoire.";
            return null;
        },
        
        telephone: (telephone) => {
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
    };

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

    // Fonction pour valider un champ
    function validateField(field, validator, ...args) {
        const value = field.value.trim();
        const error = validator(value, ...args);
        
        if (error) {
            showError(field, error);
            return false;
        } else {
            clearError(field);
            return true;
        }
    }

    // Validation en temps réel pour le formulaire de connexion
    if (loginForm) {
        const loginEmail = loginForm.querySelector('#login-email');
        const loginPassword = loginForm.querySelector('#login-password');

        // Validation de l'email
        loginEmail.addEventListener('blur', () => {
            validateField(loginEmail, validators.email);
        });

        // Validation du mot de passe
        loginPassword.addEventListener('blur', () => {
            validateField(loginPassword, validators.password);
        });

        // Soumission du formulaire de connexion
        loginForm.addEventListener('submit', (e) => {
            const emailValid = validateField(loginEmail, validators.email);
            const passwordValid = validateField(loginPassword, validators.password);
            
            if (!emailValid || !passwordValid) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Validation en temps réel pour le formulaire d'inscription
    if (registerForm) {
        const registerNom = registerForm.querySelector('#register-nom');
        const registerPrenom = registerForm.querySelector('#register-prenom');
        const registerEmail = registerForm.querySelector('#register-email');
        const registerPassword = registerForm.querySelector('#register-password');
        const registerPasswordConfirm = registerForm.querySelector('#register-password-confirm');
        const registerCivilite = registerForm.querySelector('#register-civilite');
        const registerTelephone = registerForm.querySelector('#register-tel');

        // Validation du nom
        registerNom.addEventListener('blur', () => {
            validateField(registerNom, validators.name, 'nom');
        });

        // Validation du prénom
        registerPrenom.addEventListener('blur', () => {
            validateField(registerPrenom, validators.name, 'prénom');
        });

        // Validation de l'email
        registerEmail.addEventListener('blur', () => {
            validateField(registerEmail, validators.email);
        });

        // Validation du mot de passe
        registerPassword.addEventListener('blur', () => {
            validateField(registerPassword, validators.password);
        });

        // Validation de la confirmation du mot de passe
        registerPasswordConfirm.addEventListener('blur', () => {
            const password = registerPassword.value;
            const confirmPassword = registerPasswordConfirm.value;
            
            if (!confirmPassword) {
                showError(registerPasswordConfirm, "La confirmation du mot de passe est obligatoire.");
                return;
            }
            
            if (password !== confirmPassword) {
                showError(registerPasswordConfirm, "Les mots de passe ne correspondent pas.");
                return;
            }
            
            clearError(registerPasswordConfirm);
        });

        // Validation de la civilité
        registerCivilite.addEventListener('change', () => {
            validateField(registerCivilite, validators.civilite);
        });

        // Validation du téléphone
        registerTelephone.addEventListener('blur', () => {
            validateField(registerTelephone, validators.telephone);
        });

        // Soumission du formulaire d'inscription
        registerForm.addEventListener('submit', (e) => {
            const nomValid = validateField(registerNom, validators.name, 'nom');
            const prenomValid = validateField(registerPrenom, validators.name, 'prénom');
            const emailValid = validateField(registerEmail, validators.email);
            const passwordValid = validateField(registerPassword, validators.password);
            const civiliteValid = validateField(registerCivilite, validators.civilite);
            const telephoneValid = validateField(registerTelephone, validators.telephone);
            
            // Validation de la confirmation du mot de passe
            const password = registerPassword.value;
            const confirmPassword = registerPasswordConfirm.value;
            let confirmPasswordValid = true;
            
            if (!confirmPassword) {
                showError(registerPasswordConfirm, "La confirmation du mot de passe est obligatoire.");
                confirmPasswordValid = false;
            } else if (password !== confirmPassword) {
                showError(registerPasswordConfirm, "Les mots de passe ne correspondent pas.");
                confirmPasswordValid = false;
            } else {
                clearError(registerPasswordConfirm);
            }
            
            if (!nomValid || !prenomValid || !emailValid || !passwordValid || !civiliteValid || !confirmPasswordValid) {
                e.preventDefault();
                return false;
            }
            
            // Validation supplémentaire du téléphone si fourni
            if (registerTelephone.value.trim() && !telephoneValid) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Fonction pour basculer entre connexion et inscription
    function toggleAuthSections() {
        if (loginSection && registerSection) {
            loginSection.classList.toggle('hidden');
            loginSection.classList.toggle('visible');
            registerSection.classList.toggle('hidden');
            registerSection.classList.toggle('visible');
        }
    }

    // Écouter les clics sur les liens de basculement
    if (showRegisterLink) {
        showRegisterLink.addEventListener('click', function(e) {
            e.preventDefault();
            toggleAuthSections();
        });
    }

    if (showLoginLink) {
        showLoginLink.addEventListener('click', function(e) {
            e.preventDefault();
            toggleAuthSections();
        });
    }

    // Nettoyage automatique des erreurs lors de la saisie
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.classList.contains('is-valid') || input.classList.contains('is-invalid')) {
                clearError(input);
            }
        });
    });

    // Scroll automatique vers le formulaire d'inscription si besoin
    if (typeof showRegisterVar !== 'undefined' && showRegisterVar) {
        if (registerSection) {
            registerSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
});

// Fonction de validation du mot de passe selon le pattern demandé
function validatePassword(password) {
    const length = password.length >= 8;
    const uppercase = (password.match(/[A-Z]/g) || []).length >= 2;
    const number = /[0-9]/.test(password);
    const special = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    
    return { length, uppercase, number, special };
}

// Fonction de mise à jour des indicateurs de validation du mot de passe
function updatePasswordRequirements(password, containerId = 'password-requirements-indicators') {
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

// Initialisation de la validation du mot de passe pour le formulaire d'inscription utilisateur
function initUserRegisterValidation() {
    const password = document.getElementById('register-password');
    const passwordConfirm = document.getElementById('register-password-confirm');
    const form = document.getElementById('register-form');
    
    if (password && passwordConfirm && form) {
        password.addEventListener('input', function() {
            updatePasswordRequirements(this.value);
            checkPasswordMatch('register-password', 'register-password-confirm', 'password-match');
        });
        
        passwordConfirm.addEventListener('input', function() {
            checkPasswordMatch('register-password', 'register-password-confirm', 'password-match');
        });
        
        form.addEventListener('submit', function(e) {
            const requirements = validatePassword(password.value);
            const allValid = requirements.length && requirements.uppercase && requirements.number && requirements.special;
            const passwordsMatch = checkPasswordMatch('register-password', 'register-password-confirm', 'password-match');
            
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

// Initialisation automatique quand le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    initUserRegisterValidation();
});

