<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class Contact {

    /**
     * Envoie un email à l'administrateur depuis le formulaire de contact.
     *
     * @param string $nom Le nom de l'expéditeur.
     * @param string $prenom Le prénom de l'expéditeur.
     * @param string $email L'email de l'expéditeur.
     * @param string $sujet Le sujet du message.
     * @param string $message Le contenu du message.
     * @return bool True si l'email est envoyé, false sinon.
     */
    public function sendContactEmail($nom, $prenom, $email, $sujet, $message) {
        $mailer = new PHPMailer(true);
        try {
            // --- CONFIGURATION SMTP ---
            $mailer->isSMTP();
            $mailer->Host       = 'dwwm2425.fr';
            $mailer->SMTPAuth   = true;
            $mailer->Username   = 'contact@dwwm2425.fr';
            $mailer->Password   = '!cci18000Bourges!';
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mailer->Port       = 465; 
            $mailer->CharSet = 'UTF-8';

            // --- EXPÉDITEUR ET DESTINATAIRE ---
            $mailer->setFrom('contact@dwwm2425.fr', 'Elessard Bijouterie - Contact');
            $mailer->addAddress('test-ct09jdw4e@srv1.mail-tester.com', 'Administrateur');
            $mailer->addReplyTo($email, $nom . ' ' . $prenom);

            // --- CONTENU DE L'EMAIL ---
            $mailer->isHTML(true);
            $mailer->Subject = 'Nouveau message : ' . htmlspecialchars($sujet);
            $mailer->Body    = $this->generateEmailBody($nom, $prenom, $email, $sujet, $message);
            $mailer->AltBody = $this->generateEmailText($nom, $prenom, $email, $sujet, $message);

            $mailer->send();
            return true;

        } catch (Exception $e) {
            error_log("Erreur PHPMailer: " . $mailer->ErrorInfo);
            return false;
        }
    }

    /**
     * Génère le corps HTML de l'email de contact.
     */
    private function generateEmailBody($nom, $prenom, $email, $sujet, $message) {
        $nom = htmlspecialchars($nom);
        $prenom = htmlspecialchars($prenom);
        $email = htmlspecialchars($email);
        $sujet = htmlspecialchars($sujet);
        $message = nl2br(htmlspecialchars($message));

        return "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family: Arial, sans-serif; color: #333;'>
            <div style='max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
                <h2 style='color: #2c3e50;'>Nouveau message depuis votre site</h2>
                <p>Vous avez reçu un nouveau message via le formulaire de contact.</p>
                <hr>
                <p><strong>Nom :</strong> {$prenom} {$nom}</p>
                <p><strong>Email :</strong> <a href='mailto:{$email}'>{$email}</a></p>
                <p><strong>Sujet :</strong> {$sujet}</p>
                <div style='margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;'>
                    <h3 style='margin-top: 0;'>Message :</h3>
                    <p>{$message}</p>
                </div>
            </div>
        </body>
        </html>";
    }

    /**
     * Génère le corps texte de l'email de contact.
     */
    private function generateEmailText($nom, $prenom, $email, $sujet, $message) {
        return "Nouveau message depuis le formulaire de contact.\n\n" .
               "Nom : {$prenom} {$nom}\n" .
               "Email : {$email}\n" .
               "Sujet : {$sujet}\n\n" .
               "Message :\n" .
               "----------------------\n" .
               $message;
    }
} 