<main class="article-details-container">
    <article class="article-details">
        <h1 class="article-title"><?php echo htmlspecialchars($article['Titre_Article']); ?></h1>
        <div class="article-meta">
            <span class="article-date"><?php echo htmlspecialchars($article['DatePublication_Article']); ?></span>
            <span class="article-category"><?php echo htmlspecialchars($article['Nom_Categorie']); ?></span>
        </div>
        <?php 
        $images = $article['images'] ? explode(',', $article['images']) : [];
        $imageUrl = !empty($images) ? $images[0] : 'assets/images/default-article.jpg';
        ?>
        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($article['Titre_Article']); ?>" class="article-image">
        <div class="article-content">
            <?php echo nl2br(htmlspecialchars($article['Contenue_Article'])); ?>
        </div>
    </article>
</main>
