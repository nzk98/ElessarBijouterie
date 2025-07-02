# ElessardBijouterie

## Description

ElessardBijouterie est une application web de gestion et de présentation pour une bijouterie. Elle permet la gestion des articles, des créations, des commandes, des utilisateurs, ainsi qu'une interface d'administration complète.

## Fonctionnalités

- Gestion des articles et créations (ajout, modification, suppression)
- Gestion des utilisateurs (inscription, connexion, profil)
- Gestion du panier et des commandes
- Interface d'administration (catégories, matières, carrousel, logs, utilisateurs, etc.)
- Pages de présentation, contact, blog, CGV, mentions légales, etc.
- Envoi d'e-mails via PHPMailer

## Technologies utilisées

- **Backend** : PHP (architecture MVC artisanale)
- **Frontend** : HTML, CSS, JavaScript
- **Base de données** : MySQL
- **Librairies externes** : PHPMailer

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone <url-du-dépôt>
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Configurer la base de données**
   - Les paramètres de connexion se trouvent dans `app/models/Database.php` :
     ```php
     $host = "localhost";
     $dbname = "elessardbijouterie";
     $username = "root";
     $password = "";
     ```
   - Modifie ces valeurs selon ta configuration locale.

4. **Importer la base de données**
   - Un fichier de documentation SQL se trouve dans `Doc/SQL.docx`.
   - Crée la base de données `elessardbijouterie` et importe les tables selon ce document.

5. **Lancer le serveur local**
   - Utilise XAMPP, WAMP, MAMP ou le serveur PHP intégré :
     ```bash
     php -S localhost:8000
     ```
   - Accède à l'application via [http://localhost:8000](http://localhost:8000) ou selon ton environnement.

6. **(Optionnel) Configurer les droits d'accès**
   - Assure-toi que les dossiers d'images (`assets/images/articles/`, `assets/images/creations/`) sont accessibles en écriture si tu utilises l'upload.

## Structure du projet

- `app/controllers/` : Contrôleurs (logique métier)
- `app/models/` : Modèles (accès aux données)
- `app/views/` : Vues (pages HTML)
- `assets/` : Ressources statiques (CSS, JS, images)
- `vendor/` : Dépendances installées par Composer
- `Doc/` : Documentation, diagrammes, maquettes, fichiers SQL

## Auteur

- Nicolas Zinck

## Licence

Ce projet est propriétaire.  
Tous droits réservés.  
Aucune utilisation, copie, modification ou distribution n'est autorisée sans l'accord explicite de l'auteur.

## Contact

- [Ton adresse e-mail ou autre moyen de contact]

## Annexes

- Diagrammes UML, MCD, MLD, maquettes graphiques et autres documents dans le dossier `Doc/`.

## À faire

- Intégration du module de paiement Stripe pour le règlement des commandes en ligne
- Finalisation et amélioration de la gestion des commandes (validation, suivi, historique, notifications)
- Tests et sécurisation des processus de commande et de paiement
- Optimisation de l'expérience utilisateur lors du passage de commande 