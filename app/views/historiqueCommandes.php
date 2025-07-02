
<main>
  <h1>Historique des commandes</h1>
  <section aria-label="Liste des commandes">
    <!-- Commande 1 -->
    <article class="commande-card">
      <div class="commande-infos">
        <h2>Commande n°12345</h2>
        <time datetime="2024-03-15">15 mars 2024</time>
        <span class="statut statut-livree">Livrée</span>
      </div>
      <section class="commande-produits" aria-label="Produits de la commande 12345">
        <article class="produit-item">
          <img src="assets/images/article1.jpg" alt="Bague en or 18 carats" class="produit-image" >
          <div class="produit-details">
            <h3>Bague en or 18 carats</h3>
            <p>Quantité : <span>1</span> — <span>599,99€</span></p>
          </div>
        </article>
        <article class="produit-item">
          <img src="assets/images/article2.jpg" alt="Collier en argent" class="produit-image" >
          <div class="produit-details">
            <h3>Collier en argent</h3>
            <p>Quantité : <span>1</span> — <span>299,99€</span></p>
          </div>
        </article>
      </section>
      <div class="commande-total">
        <strong>Total: 899,98€</strong>
      </div>
    </article>

    <!-- Commande 2 -->
    <article class="commande-card">
      <div class="commande-infos">
        <h2>Commande n°12344</h2>
        <time datetime="2024-03-10">10 mars 2024</time>
        <span class="statut statut-en-preparation">En préparation</span>
      </div>
      <section class="commande-produits" aria-label="Produits de la commande 12344">
        <article class="produit-item">
          <img src="assets/images/article3.jpg" alt="Bracelet en or blanc" class="produit-image" loading="lazy">
          <div class="produit-details">
            <h3>Bracelet en or blanc</h3>
            <p>Quantité : <span>1</span> — <span>799,99€</span></p>
          </div>
        </article>
      </section>
      <div class="commande-total">
        <strong>Total: 799,99€</strong>
      </div>
    </article>

    <!-- Commande 3 -->
    <article class="commande-card">
      <div class="commande-infos">
        <h2>Commande n°12343</h2>
        <time datetime="2024-03-05">5 mars 2024</time>
        <span class="statut statut-en-preparation">En préparation</span>
      </div>
      <section class="commande-produits" aria-label="Produits de la commande 12343">
        <article class="produit-item">
          <img src="assets/images/article1.jpg" alt="Boucles d'oreilles en diamant" class="produit-image" loading="lazy">
          <div class="produit-details">
            <h3>Boucles d'oreilles en diamant</h3>
            <p>Quantité : <span>1</span> — <span>1299,99€</span></p>
          </div>
        </article>
      </section>
      <div class="commande-total">
        <strong>Total: 1299,99€</strong>
      </div>
    </article>
  </section>
  <nav class="actions" aria-label="Actions de navigation">
    <a href="index.php?page=Dashboard" class="btn btn-secondary">Retour au tableau de bord</a>
  </nav>
</main> 
