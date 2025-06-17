<div class="admin-main">
    <div class="admin-header">
        <h1>Gérer les matières</h1>
    </div>
    <div class="admin-form-box">
        <div class="admin-categories-row">
            <div class="admin-categories-col">
                <h2>Matières</h2>
                <form method="POST" action="index.php?page=AdminMatieres">
                    <input type="text" name="nom_matiere" placeholder="Nom matière" required>
                    <button type="submit" name="add_matiere">Ajouter</button>
                </form>
                <ul>
                    <?php foreach ($matieres as $matiere): ?>
                        <li>
                            <?= htmlspecialchars($matiere->nom) ?>
                            <a class="delete-link" href="index.php?page=AdminMatieres&delete=<?= $matiere->id ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ?')">🗑️</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div> 