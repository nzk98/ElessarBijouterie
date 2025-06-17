<!-- Contenu principal -->
<div class="admin-container">
    

    <!-- Main Content -->
    <div class="admin-main">
        <div class="admin-header">
            <div class="header-left">
                <h1>Création d'un nouveau produit</h1>
            </div>
        </div>

        <div class="admin-form-box">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=AdminFormCreation" method="POST" enctype="multipart/form-data" class="admin-form">
                <?php if ($creation_a_modifier): ?>
                    <input type="hidden" name="id" value="<?= $creation_a_modifier->getId() ?>">
                <?php endif; ?>
                
                <div class="form-section">
                    <h2>Informations générales</h2>
                    <div class="form-group">
                        <label for="nom">Nom du produit</label>
                        <input type="text" class="form-control" id="nom" name="nom" required 
                               value="<?= $creation_a_modifier ? htmlspecialchars($creation_a_modifier->getNom()) : '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="8" required><?= $creation_a_modifier ? htmlspecialchars($creation_a_modifier->getDescription()) : '' ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Détails du produit</h2>
                    <div class="form-group">
                        <label for="prix">Prix €</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="prix" name="prix" step="0.01" required
                                   value="<?= $creation_a_modifier ? $creation_a_modifier->getPrix() : '' ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="stock" name="stock" min="0"
                                   value="<?= $creation_a_modifier ? $creation_a_modifier->getStock() : '0' ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach ($categories_creation as $cat): ?>
                                <option value="<?= $cat->getId() ?>" <?= ($creation_a_modifier && $creation_a_modifier->getIdCategorie() == $cat->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->getNom()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Matières</label>
                        <div class="checkbox-group">
                            <?php foreach ($matieres as $matiere): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" 
                                           id="matiere_<?= $matiere->id ?>" 
                                           name="matieres[]" 
                                           value="<?= $matiere->id ?>"
                                           <?= ($creation_a_modifier && in_array($matiere->id, $matieres_creation)) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="matiere_<?= $matiere->id ?>">
                                        <?= htmlspecialchars($matiere->nom) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Images du produit</h2>
                    <?php if ($creation_a_modifier && !empty($creation_a_modifier->getImages())): ?>
                        <div class="current-images">
                            <h3>Images actuelles</h3>
                            <div class="image-grid">
                                <?php foreach ($creation_a_modifier->getImages() as $key => $image): ?>
                                    <div class="image-item">
                                        <img src="<?= htmlspecialchars($image) ?>" alt="Image du produit">
                                        <button type="button" class="delete-image" onclick="deleteCreationImage(<?= $creation_a_modifier->getId() ?>, '<?= htmlspecialchars($image) ?>', this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="image">Nouvelles images (JPG, PNG, GIF, max 5Mo chacune) :</label>
                        <input type="file" name="image[]" id="image" accept="image/jpeg, image/png, image/gif" multiple>
                        <small>Formats acceptés : JPG, PNG, GIF. Taille maximale : 5MB</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="admin-submit">
                        <i class="fas fa-save"></i> <?= $creation_a_modifier ? 'Modifier' : 'Créer' ?> le produit
                    </button>
                    <a href="index.php?page=AdminCreations" class="admin-reset">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

