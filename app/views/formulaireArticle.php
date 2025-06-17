<!-- Contenu principal -->
<div class="admin-container">
    <!-- Main Content -->
    <div class="admin-main">
        <div class="admin-header">
            <div class="header-left">
                <h1><?php echo $article_a_modifier ? 'Modifier l\'article' : 'Créer un nouvel article'; ?></h1>
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

            <form action="index.php?page=AdminFormArticle" method="POST" class="admin-form" enctype="multipart/form-data">
                <?php if ($article_a_modifier): ?>
                    <input type="hidden" name="id" value="<?php echo $article_a_modifier['ID_Article']; ?>">
                <?php endif; ?>

                <div class="form-section">
                    <h2>Informations générales</h2>
                    <div class="form-group">
                        <label for="titre">Titre de l'article</label>
                        <input type="text" class="form-control" id="titre" name="titre" required 
                               value="<?php echo $article_a_modifier ? htmlspecialchars($article_a_modifier['Titre_Article']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="contenu">Contenu</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="8" required><?php echo $article_a_modifier ? htmlspecialchars($article_a_modifier['Contenue_Article']) : ''; ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2>Catégorie</h2>
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach ($categories_article as $cat): ?>
                                <option value="<?php echo $cat->getId(); ?>" <?php echo ($article_a_modifier && $article_a_modifier['ID_Categorie'] == $cat->getId()) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat->getNom()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
                                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Image de l'article">
                                        <button type="button" class="delete-image" onclick="deleteArticleImage(<?php echo $article_a_modifier['ID_Article']; ?>, '<?php echo htmlspecialchars($image); ?>', this)">
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
                        <i class="fas fa-save"></i> <?php echo $article_a_modifier ? 'Modifier' : 'Créer'; ?> l'article
                    </button>
                    <a href="index.php?page=AdminArticles" class="admin-reset">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
