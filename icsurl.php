<?php
$page_title = 'Calendrier';
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
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-calendar2-week"></i></div>
                <h1 class="fw-bolder">Exporter les préventifs</h1>
                <p class="lead fw-normal text-muted mb-4">Essayer de le faire automatiquement sinon regardez plus bas</p>
                <a class="btn btn-primary btn-lg" href="webcal://croix-rouge-ixelles.com/ics.php?id=<?php echo $user['id'] ?>">Automatique TOUS les prev.</a>
                </br>
                </br>
                <a class="btn btn-primary btn-lg" href="webcal://croix-rouge-ixelles.com/icsperso.php?id=<?php echo $user['id'] ?>">Automatique VOS prev.</a>
            </div>
            <div class="row gx-5">
                <div class="col-lg-4 mb-5">
                    <div class="card h-500 shadow border-0">
                        <img class="card-img-top" src="./libs/img/gagenda.png" alt="..." />
                        <div class="card-body p-4">
                            <a class="text-decoration-none link-dark stretched-link" href="googleagenda">
                                <h5 class="card-title mb-3">Google Agenda</h5>
                            </a>
                            <p class="card-text mb-0">Cliquez pour voir comment ajouter le calendrier.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-5">
                    <div class="card h-500 shadow border-0">
                        <img class="card-img-top" src="./libs/img/apple.png" alt="..." />
                        <div class="card-body p-4">
                            <a class="text-decoration-none link-dark stretched-link" href="applecalendar">
                                <h5 class="card-title mb-3">Apple Calendar</h5>
                            </a>
                            <p class="card-text mb-0">Cliquez pour voir comment ajouter le calendrier.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-5">
                    <div class="card h-500 shadow border-0">
                        <img class="card-img-top" src="./libs/img/outlook.jpg" alt="..." />
                        <div class="card-body p-4">
                            <a class="text-decoration-none link-dark stretched-link" href="outlook">
                                <h5 class="card-title mb-3">[Expérimental] Outlook</h5>
                            </a>
                            <p class="card-text mb-0">Cliquez pour voir comment ajouter le calendrier.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>












<?php include_once('layouts/footer.php'); ?>