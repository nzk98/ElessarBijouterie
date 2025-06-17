
<body>
    <div class="admin-container">
        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <h1>Tableau de bord</h1>
                </div>
                <div class="header-right">
                    <div class="admin-profile">
                        <span>Bienvenue, <?php echo $_SESSION['admin_user']['nom'] ?? 'Admin'; ?></span>
                        <a href="?page=admin&section=logout" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </div>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- Statistiques -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Produits</h3>
                            <p><?php echo $nbCreations; ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Commandes</h3>
                            <p><?php echo $nbCommandes; ?></p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>Utilisateurs</h3>
                            <p><?php echo $nbUtilisateurs; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="quick-actions">
                    <h2>Actions rapides</h2>
                    <div class="action-buttons">
                        <a href="?page=admin&section=products&action=create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau produit
                        </a>
                        <a href="?page=admin&section=categories&action=create" class="btn btn-success">
                            <i class="fas fa-plus"></i> Nouvelle catégorie
                        </a>
                        <a href="?page=admin&section=orders" class="btn btn-info">
                            <i class="fas fa-list"></i> Voir les commandes
                        </a>
                    </div>
                </div>

                <!-- Dernières commandes -->
                <div class="recent-orders">
                    <h2>Dernières commandes</h2>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#1234</td>
                                    <td>Jean Dupont</td>
                                    <td>2024-03-20</td>
                                    <td>150€</td>
                                    <td><span class="badge bg-success">Livrée</span></td>
                                </tr>
                                <tr>
                                    <td>#1233</td>
                                    <td>Marie Martin</td>
                                    <td>2024-03-19</td>
                                    <td>75€</td>
                                    <td><span class="badge bg-warning">En cours</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/<?php echo $jsFile; ?>"></script>
</body>
</html>
