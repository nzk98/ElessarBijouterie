<?php
// Récupération des variables du contrôleur pour plus de clarté
$creation_a_modifier = $this->getCreationAModifier();
$matieres_creation = $this->getMatieresCreation();
$errors = $this->getErrors();
$categories_creation = $this->getCategoriesCreation();
$matieres = $this->getMatieres();
?>
<!-- Contenu principal -->
<div class="admin-container">
    
    <!-- Main Content -->
    <div class="admin-main">
        <div class="admin-header">
            <div class="header-left">
                <h1><?= $creation_a_modifier ? 'Modifier le produit' : 'Création d\'un nouveau produit' ?></h1>
            </div>
        </div>

        <div class="admin-form-box">
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
            <?php endif; ?>

            <form action="index.php?page=AdminFormCreation<?= $creation_a_modifier ? '&id=' . $creation_a_modifier->getId() : '' ?>" method="POST" enctype="multipart/form-data" class="admin-form">
                <?php if ($creation_a_modifier): ?>
                    <input type="hidden" name="id" value="<?= $creation_a_modifier->getId() ?>">
                <?php endif; ?>
                
                <div class="form-section">
                    <h2>Informations générales</h2>
                    <div class="form-group <?= isset($errors['nom']) ? 'has-error' : '' ?>">
                        <label for="nom">Nom du produit</label>
                        <input type="text" class="form-control" id="nom" name="nom" required 
                               value="<?= htmlspecialchars($_POST['nom'] ?? ($creation_a_modifier ? $creation_a_modifier->getNom() : '')) ?>">
                        <?php if (isset($errors['nom'])): ?><span class="error-message"><?= $errors['nom'] ?></span><?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($errors['description']) ? 'has-error' : '' ?>">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="8" required><?= htmlspecialchars($_POST['description'] ?? ($creation_a_modifier ? $creation_a_modifier->getDescription() : '')) ?></textarea>
                        <?php if (isset($errors['description'])): ?><span class="error-message"><?= $errors['description'] ?></span><?php endif; ?>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Détails du produit</h2>
                    <div class="form-group <?= isset($errors['prix']) ? 'has-error' : '' ?>">
                        <label for="prix">Prix €</label>
                        <input type="number" class="form-control" id="prix" name="prix" step="0.01" required
                                   value="<?= htmlspecialchars($_POST['prix'] ?? ($creation_a_modifier ? $creation_a_modifier->getPrix() : '')) ?>">
                        <?php if (isset($errors['prix'])): ?><span class="error-message"><?= $errors['prix'] ?></span><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0"
                                   value="<?= htmlspecialchars($_POST['stock'] ?? ($creation_a_modifier ? $creation_a_modifier->getStock() : '0')) ?>">
                    </div>

                    <div class="form-group <?= isset($errors['categorie']) ? 'has-error' : '' ?>">
                        <label for="categorie">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php 
                            $selected_cat = $_POST['categorie'] ?? ($creation_a_modifier ? $creation_a_modifier->getIdCategorie() : '');
                            foreach ($categories_creation as $cat): ?>
                                <option value="<?= $cat->getId() ?>" <?= ($selected_cat == $cat->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->getNom()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['categorie'])): ?><span class="error-message"><?= $errors['categorie'] ?></span><?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($errors['matieres']) ? 'has-error' : '' ?>">
                        <label>Matières</label>
                        <div class="checkbox-group">
                            <?php 
                            $selected_matieres = $_POST['matieres'] ?? ($creation_a_modifier ? $matieres_creation : []);
                            foreach ($matieres as $matiere): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" 
                                           id="matiere_<?= $matiere->id ?>" 
                                           name="matieres[]" 
                                           value="<?= $matiere->id ?>"
                                           <?= in_array($matiere->id, $selected_matieres) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="matiere_<?= $matiere->id ?>">
                                        <?= htmlspecialchars($matiere->nom) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (isset($errors['matieres'])): ?><span class="error-message"><?= $errors['matieres'] ?></span><?php endif; ?>
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
                    
                    <div class="form-group <?= isset($errors['image']) ? 'has-error' : '' ?>">
                        <label for="image">Nouvelles images (JPG, PNG, GIF) :</label>
                        <input type="file" name="image[]" id="image" accept="image/jpeg, image/png, image/gif" multiple>
                        <?php if (isset($errors['image'])): ?><span class="error-message"><?= $errors['image'] ?></span><?php endif; ?>
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

