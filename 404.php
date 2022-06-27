<?php $page_title = "Erreur 404";
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
    redirect('index', false);
};
$user = current_user();
include_once('./layouts/header.php'); ?>

<header class="py-3">
    <div class="container px-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xxl-6">
                <div class="text-center my-5">
                    <h1 class="fw-bolder mb-3">404</h1>
                    <p class="lead fw-normal text-muted mb-4">PAGE NON TROUVÉE</p>
                    <p class="text-muted">Il y a moyen c'est moi qui ai fait une erreur</p>
                    <a class="btn btn-primary btn-lg" href="https://croix-rouge-ixelles.com/index">Retourner à l'accueil</a>
                </div>
            </div>
        </div>
    </div>
</header>
</main>
<?php include_once('./layouts/footer.php'); ?>