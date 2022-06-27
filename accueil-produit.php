<?php
$page_title = 'Produit';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
    redirect('index', false);
}
$user = current_user();
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
    <div class="container px-2">
        <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
            <div class="text-center mb-5">
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-box-seam"></i></div>
                <h1 class="fw-bolder">Inventaire</h1>
            </div>
            <div class="row gx-5">
                <div class="col">
                    <div class="card h-500 shadow border-0">
                        <div class="card-body p-4">
                            <a class="text-decoration-none link-dark stretched-link" href="produit">
                                <h5 class="card-title mb-3">Tout les produits</h5>
                            </a>
                            <p class="card-text mb-0">Cliquez pour accéder à tous les produits.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-500 shadow border-0">
                        <div class="card-body p-4">
                            <a class="text-decoration-none link-dark stretched-link" href="pre-produit-cat">
                                <h5 class="card-title mb-3">Médicalisée 1er étage</h5>
                            </a>
                            <p class="card-text mb-0">Cliquez pour accéder au schéma.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-500 shadow border-0">
                        <div class="card-body p-4">
                            <a class="text-decoration-none link-dark stretched-link" href="categorie">
                                <h5 class="card-title mb-3">Gérer les catégories</h5>
                            </a>
                            <p class="card-text mb-0">Cliquez pour accéder aux catégories.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>












<?php include_once('layouts/footer.php'); ?>