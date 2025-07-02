<!-- Section principale de la page d'accueil -->
<main>
    <!-- Carrousel -->
    <section class="carousel" aria-label="Carrousel de bijoux">
        <div id="main-carousel" class="carousel-container">
            <?php 
            $totalImages = count($carouselImages);
            // Debug: afficher le nombre d'images
            if (isset($_GET['debug'])) {
                echo "<!-- Debug: Nombre d'images du carrousel: $totalImages -->";
                echo "<!-- Debug: Images: " . print_r($carouselImages, true) . " -->";
            }
            
            if ($totalImages > 0):
                $currentImage = isset($_GET['slide']) ? (int)$_GET['slide'] : 0;
                $currentImage = max(0, min($currentImage, $totalImages - 1));
            ?>
                <!-- Toutes les images du carrousel -->
                <?php foreach ($carouselImages as $index => $image): ?>
                    <div class="carousel-item <?php echo $index === $currentImage ? 'active' : ''; ?>" 
                         data-slide="<?php echo $index; ?>">
                        <img src="<?php echo htmlspecialchars($image->getUrlImage()); ?>" 
                             alt="Image de bijou" 
                             class="carousel-image">
                    </div>
                <?php endforeach; ?>
                
                <div class="carousel-navigation">
                    <button class="carousel-control prev" aria-label="Image précédente" data-direction="prev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="carousel-indicators">
                        <?php for ($i = 0; $i < $totalImages; $i++): ?>
                            <button class="carousel-indicator <?php echo $i === $currentImage ? 'active' : ''; ?>"
                                    data-slide="<?php echo $i; ?>"
                                    aria-label="Aller à l'image <?php echo $i + 1; ?>">
                            </button>
                        <?php endfor; ?>
                    </div>
                    <button class="carousel-control next" aria-label="Image suivante" data-direction="next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            <?php else: ?>
                <div class="carousel-item active">
                    <p class="no-images">Aucune image disponible dans le carrousel</p>
                    <?php if (isset($_GET['debug'])): ?>
                        <p class="debug-message">Debug: Aucune image trouvée dans la base de données</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Articles du blog mis en avant -->
    <section class="featured-posts">
        <h2>Derniers Articles</h2>
        <div class="posts-grid">
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): ?>
                    <article class="post-card">
                        <?php 
                        $images = $article['images'] ? explode(',', $article['images']) : [];
                        $imageUrl = !empty($images) ? $images[0] : 'assets/images/default-article.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($article['Titre_Article']); ?>">
                        <div class="post-content">
                            <span class="post-category"><?php echo htmlspecialchars($article['Nom_Categorie']); ?></span>
                            <h3><?php echo htmlspecialchars($article['Titre_Article']); ?></h3>
                            <p><?php echo htmlspecialchars(mb_strimwidth($article['Contenue_Article'], 0, 100, '...')); ?></p>
                            <div class="post-meta">
                                <span class="post-date"><?php echo htmlspecialchars($article['DatePublication_Article']); ?></span>
                            </div>
                            <a href="index.php?page=Article&id=<?php echo $article['ID_Article']; ?>" class="read-more">Lire la suite</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-articles">Aucun article disponible pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>
</main>