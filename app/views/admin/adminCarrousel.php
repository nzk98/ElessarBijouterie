<div class="admin-main">
    <div class="admin-header">
        <div class="header-left">
            <h1>Gestion du Carrousel</h1>
        </div>
    </div>

    <div class="admin-form-box">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <p class="lead">Sélectionnez les images de vos créations à afficher dans le carrousel de la page d'accueil.</p>
        
        <div class="carousel-grid">
            <?php foreach ($creations as $creation): ?>
                <?php foreach ($creation->getImages() as $image): ?>
                    <?php
                    $isInCarousel = false;
                    foreach ($carouselImages as $carouselImage) {
                        if ($carouselImage->getUrlImage() === $image) {
                            $isInCarousel = true;
                            break;
                        }
                    }
                    ?>
                    <div class="carousel-item-admin<?php echo $isInCarousel ? ' in-carousel' : ''; ?>">
                        <div class="carousel-caption-admin-top">
                            <?php echo htmlspecialchars($creation->getNom()); ?>
                        </div>
                        <div class="carousel-img-admin-wrapper">
                            <img src="<?php echo htmlspecialchars($image); ?>" 
                                 alt="<?php echo htmlspecialchars($creation->getNom()); ?>" 
                                 class="admin-carousel-thumb">
                        </div>
                        <form action="index.php?page=AdminCarousel&action=toggleCarousel" method="POST" class="carousel-form">
                            <input type="hidden" name="creation_id" value="<?php echo $creation->getId(); ?>">
                            <input type="hidden" name="image_url" value="<?php echo htmlspecialchars($image); ?>">
                            <button type="submit" class="btn <?php echo $isInCarousel ? 'btn-danger' : 'btn-primary'; ?> btn-sm btn-carousel-admin<?php echo $isInCarousel ? ' in-carousel-btn' : ' add-carousel-btn'; ?>">
                                <?php if ($isInCarousel): ?>
                                    <i class="fas fa-times-circle"></i> Retirer du carrousel
                                <?php else: ?>
                                    <i class="fas fa-plus-circle"></i> Ajouter au carrousel
                                <?php endif; ?>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

