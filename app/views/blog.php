<main class="blog-container">
    <h2 class="blog-title">Blog</h2>
    
    <div class="blog-content">
        <!-- Filtres -->
        <aside class="blog-filters">
            <form id="filter-form" action="index.php" method="GET">
                <input type="hidden" name="page" value="Blog">
                
                <h3>Catégories</h3>
                <div class="filter-options">
                    <?php foreach ($categories as $categorie): ?>
                        <label class="filter-option">
                            <input type="checkbox" name="categories[]" value="<?php echo $categorie->getId(); ?>"
                                <?php echo in_array($categorie->getId(), $selectedCategories) ? 'checked' : ''; ?>>
                            <?php echo htmlspecialchars($categorie->getNom()); ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <h3>Archives</h3>
                <div class="filter-options">
                    <?php foreach ($years as $year): ?>
                        <label class="filter-option">
                            <input type="checkbox" name="years[]" value="<?php echo $year; ?>"
                                <?php echo in_array($year, $selectedYears) ? 'checked' : ''; ?>>
                            <?php echo $year; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">Filtrer</button>
                    <a href="index.php?page=Blog" class="btn-reset">Réinitialiser</a>
                </div>
            </form>
        </aside>

        <!-- Articles -->
        <section class="blog-articles">
            <div class="articles-header">
                <h3 class="articles-title">Articles</h3>
                <div class="sort-options">
                    <label for="sort">Trier par :</label>
                    <select id="sort" name="sort" form="filter-form">
                        <option value="recent" <?php echo $sort === 'recent' ? 'selected' : ''; ?>>Plus récents</option>
                        <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Plus anciens</option>
                        <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Plus populaires</option>
                    </select>
                </div>
            </div>

            <div class="articles-grid">
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $article): ?>
                        <article class="article-card">
                            <?php 
                            $images = $article['images'] ? explode(',', $article['images']) : [];
                            $imageUrl = !empty($images) ? $images[0] : 'assets/images/default-article.jpg';
                            ?>
                            <div class="article-image">
                                <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($article['Titre_Article']); ?>">
                                <div class="article-category"><?php echo htmlspecialchars($article['Nom_Categorie']); ?></div>
                            </div>
                            <div class="article-content">
                                <h3 class="article-title"><?php echo htmlspecialchars($article['Titre_Article']); ?></h3>
                                <p class="article-excerpt"><?php echo htmlspecialchars(mb_strimwidth($article['Contenue_Article'], 0, 150, '...')); ?></p>
                                <div class="article-meta">
                                    <span class="article-date"><?php echo htmlspecialchars($article['DatePublication_Article']); ?></span>
                                </div>
                                <a href="index.php?page=Article&id=<?php echo $article['ID_Article']; ?>" class="btn-read-more">Lire la suite</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-articles">Aucun article ne correspond à vos critères de recherche.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
</main>

<!-- Template pour les articles -->
<template id="article-template">
    <article class="article-card">
        <div class="article-image">
            <img src="" alt="">
            <div class="article-category"></div>
        </div>
        <div class="article-content">
            <h3 class="article-title"></h3>
            <p class="article-excerpt"></p>
            <div class="article-meta">
                <span class="article-date"></span>
                <span class="article-comments"></span>
            </div>
            <a href="#" class="btn-read-more">Lire la suite</a>
        </div>
    </article>
</template>
