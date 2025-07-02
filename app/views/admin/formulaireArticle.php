<?php
// Récupération des variables du contrôleur
$article_a_modifier = $this->getArticleAModifier();
$errors = $this->getErrors();
?>
<!-- Contenu principal -->
<div class="admin-container">
    <!-- Main Content -->
    <div class="admin-main">
        <div class="admin-header">
            <div class="header-left">
                <h1><?= $article_a_modifier ? 'Modifier l\'article' : 'Créer un nouvel article' ?></h1>
            </div>
        </div>

        <div class="admin-form-box">
            <?php if (isset($errors['general'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
            <?php endif; ?>

            <form action="index.php?page=AdminFormArticle<?= $article_a_modifier ? '&id=' . $article_a_modifier['ID_Article'] : '' ?>" method="POST" class="admin-form" enctype="multipart/form-data">
                <?php if ($article_a_modifier): ?>
                    <input type="hidden" name="id" value="<?= $article_a_modifier['ID_Article'] ?>">
                <?php endif; ?>

                <div class="form-section">
                    <h2>Informations générales</h2>
                    <div class="form-group <?= isset($errors['titre']) ? 'has-error' : '' ?>">
                        <label for="titre">Titre de l'article</label>
                        <input type="text" class="form-control" id="titre" name="titre" required 
                               value="<?= htmlspecialchars($_POST['titre'] ?? ($article_a_modifier['Titre_Article'] ?? '')) ?>">
                        <?php if (isset($errors['titre'])): ?><span class="error-message"><?= $errors['titre'] ?></span><?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($errors['contenu']) ? 'has-error' : '' ?>">
                        <label for="contenu">Contenu</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="8" required><?= htmlspecialchars($_POST['contenu'] ?? ($article_a_modifier['Contenue_Article'] ?? '')) ?></textarea>
                        <?php if (isset($errors['contenu'])): ?><span class="error-message"><?= $errors['contenu'] ?></span><?php endif; ?>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Catégorie</h2>
                    <div class="form-group <?= isset($errors['categorie']) ? 'has-error' : '' ?>">
                        <label for="categorie">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php 
                            $selected_cat = $_POST['categorie'] ?? ($article_a_modifier['ID_Categorie'] ?? '');
                            foreach ($categories_article as $cat): ?>
                                <option value="<?= $cat->getId() ?>" <?= ($selected_cat == $cat->getId()) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->getNom()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['categorie'])): ?><span class="error-message"><?= $errors['categorie'] ?></span><?php endif; ?>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Images de l'article</h2>
                    <?php if ($article_a_modifier && !empty($article_a_modifier['images'])): ?>
                        <div class="current-images">
                            <h3>Images actuelles</h3>
                            <div class="image-grid">
                                <?php 
                                $images = explode(',', $article_a_modifier['images']);
                                foreach ($images as $image): ?>
                                    <div class="image-item">
                                        <img src="<?= htmlspecialchars($image) ?>" alt="Image de l'article">
                                        <button type="button" class="delete-image" onclick="deleteArticleImage(<?= $article_a_modifier['ID_Article'] ?>, '<?= htmlspecialchars($image) ?>', this)">
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
                        <i class="fas fa-save"></i> <?= $article_a_modifier ? 'Modifier' : 'Créer' ?> l'article
                    </button>
                    <a href="index.php?page=AdminArticles" class="admin-reset">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
