<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Mon Tableau de Bord</h1>
        <p>Bonjour, <?= htmlspecialchars($utilisateur['Prenom_Utilisateur'] ?? 'cher client') ?> ! Bienvenue sur votre espace personnel.</p>
    </div>

    <div class="dashboard-grid">
        <!-- Carte Profil -->
        <div class="dashboard-card profile-card">
            <div class="card-icon"><i class="fas fa-user-circle"></i></div>
            <div class="card-content">
                <h3>Mon Profil</h3>
                <p><strong>Email :</strong> <?= htmlspecialchars($utilisateur['Email_Utilisateur'] ?? 'Non défini') ?></p>
                
                <?php if (!empty($utilisateur['Adresse'])): ?>
                    <p><strong>Adresse par défaut :</strong><br>
                        <?= htmlspecialchars($utilisateur['Adresse']) ?><br>
                        <?= htmlspecialchars($utilisateur['CodePostal']) ?> <?= htmlspecialchars($utilisateur['Ville']) ?>
                    </p>
                <?php else: ?>
                    <p>Vous n'avez pas encore configuré d'adresse postale.</p>
                <?php endif; ?>
                
                <a href="index.php?page=ProfilUtilisateur" class="btn-card">Modifier mon profil</a>
            </div>
        </div>

        <!-- Carte Dernière Commande -->
        <div class="dashboard-card order-card">
            <div class="card-icon"><i class="fas fa-box-open"></i></div>
            <div class="card-content">
                <h3>Ma Dernière Commande</h3>
                <?php if ($derniereCommande): 
                    try {
                        $date = new DateTime($derniereCommande['Date_Commande']);
                        $formattedDate = $date->format('d/m/Y');
                    } catch (Exception $e) {
                        $formattedDate = 'Date invalide';
                    }
                ?>
                    <p><strong>Commande N° :</strong> <?= htmlspecialchars($derniereCommande['ID_Commande']) ?></p>
                    <p><strong>Date :</strong> <?= $formattedDate ?></p>
                    <p><strong>Statut :</strong> <span class="status-badge status-<?= strtolower(htmlspecialchars(str_replace(' ', '-', $derniereCommande['Status_Commande']))) ?>"><?= htmlspecialchars($derniereCommande['Status_Commande']) ?></span></p>
                    <p><strong>Total :</strong> <?= number_format($derniereCommande['Total_Commande'], 2, ',', ' ') ?> €</p>
                <?php else: ?>
                    <p>Vous n'avez passé aucune commande pour le moment.</p>
                <?php endif; ?>
                
                <a href="index.php?page=HistoriqueCommandes" class="btn-card">Voir toutes mes commandes</a>
            </div>
        </div>
    </div>
</div>
