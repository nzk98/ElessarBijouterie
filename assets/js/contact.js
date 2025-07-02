document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const responseDiv = document.getElementById('contact-response');
    const submitBtn = document.getElementById('submit-btn');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Envoi en cours...';
            
            // --- Nettoyage des erreurs précédentes ---
            responseDiv.style.display = 'none';
            responseDiv.className = 'contact-response';
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            document.querySelectorAll('.form-group.has-error').forEach(el => el.classList.remove('has-error'));
            
            const formData = new FormData(contactForm);
            const actionUrl = BASE_URL + 'index.php?page=Contact&action=processForm';
            
            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 && body.status === 'success') {
                    // --- Succès ---
                    responseDiv.textContent = body.message;
                    responseDiv.className = 'contact-response success show';
                    contactForm.reset();
                } else if (status === 422 && body.errors) {
                    // --- Erreurs de validation ---
                    Object.keys(body.errors).forEach(field => {
                        const inputElement = document.getElementById(field);
                        if (inputElement) {
                            const formGroup = inputElement.closest('.form-group');
                            if (formGroup) {
                                formGroup.classList.add('has-error');
                                const errorSpan = document.createElement('span');
                                errorSpan.className = 'error-message';
                                errorSpan.textContent = body.errors[field];
                                // Insérer le message après le champ
                                inputElement.parentNode.insertBefore(errorSpan, inputElement.nextSibling);
                            }
                        }
                    });
                     // Afficher un message générique si nécessaire
                     responseDiv.textContent = "Veuillez corriger les erreurs ci-dessus.";
                     responseDiv.className = 'contact-response error show';

                } else {
                     // --- Autres erreurs (serveur, etc.) ---
                    throw new Error(body.message || 'Une erreur inattendue est survenue.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                responseDiv.textContent = error.message || 'Une erreur est survenue. Veuillez réessayer.';
                responseDiv.className = 'contact-response error show';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Envoyer le message';
                if (responseDiv.classList.contains('show')) {
                    responseDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    }
}); 