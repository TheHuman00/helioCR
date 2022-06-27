<?php
$page_title = 'Google Agenda';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
    redirect('index', false);
}
$user = current_user();
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
    <div class="container px-5 my-5">
        <div class="text-center mb-5">
            <h1 class="fw-bolder">Google Agenda</h1>
            <p class="lead fw-normal text-muted mb-0">Comment ajouter le calendrier?</p>
        </div>
        <div class="row gx-5">
            <div class="col-xl-8">
                <!-- FAQ Accordion 1-->
                <div class="accordion mb-5" id="accordionExample">
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingOne"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Ordinateur</button></h3>
                        <div class="accordion-collapse collapse show" id="collapseOne" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ol>
                                    <li>Rendez vous sur ce lien : <a href="https://calendar.google.com/calendar/u/0/r/settings/addbyurl" target="_blank">https://calendar.google.com/calendar/u/0/r/settings/addbyurl</a></li>
                                    <li>Collez-y ce lien pour <strong>TOUT</strong> les prev.<div class="input-group mb-3">
                                            <input class="form-control" id="floatingTextarea2" value="https://croix-rouge-ixelles.com/ics.php?id=<?php echo $user['id'] ?>" disabled />
                                            <button class="btn btn-outline-secondary" onclick="clipboardd()"><span class="bi bi-clipboard"></span></button>
                                        </div>
                                    </li>
                                    <li>Collez-y ce lien pour <strong>VOS</strong> prev. uniquement<div class="input-group mb-3">
                                            <input class="form-control" id="floatingTextarea2" value="https://croix-rouge-ixelles.com/icsperso.php?id=<?php echo $user['id'] ?>" disabled />
                                            <button class="btn btn-outline-secondary" onclick="clipboardd()"><span class="bi bi-clipboard"></span></button>
                                        </div>
                                    </li>
                                </ol>
                                <p class="text-muted">Attention le calendrier se mets à jour toutes les 6h</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="headingTwo"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Téléphone</button></h3>
                        <div class="accordion-collapse collapse" id="collapseTwo" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Faites la manipulation sur <strong>votre ordinateur</strong>
                                <ol>
                                    <li>Ouvrez le menu <i class="bi bi-list"></i></li>
                                    <li>Cochez le calendrier ajouter</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- FAQ Accordion 2-->
            </div>
            <div class="col-xl-4">
                <div class="card border-0 bg-light mt-xl-5">
                    <div class="card-body p-4 py-lg-5">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <div class="h6 fw-bolder">Besoin d'aide ?</div>
                                <p class="text-muted mb-4">
                                    Contact
                                    <br />
                                    <strong>Boen Guillaume</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<footer class="bg-dark py-4 mt-auto">
    <div class="container px-5">
        <div class="row align-items-center justify-content-between flex-column flex-sm-row">
            <div class="col-auto">
                <div class="small m-0 text-white">Copyright &copy; Helio 2021</div>
            </div>
            <div class="col-auto">
                <span class="text-white mx-1">&middot;</span>
                <a class="link-light small" href="./credits">Crédits</a>
                <span class="text-white mx-1">&middot;</span>
            </div>
        </div>
    </div>
</footer>
<script>
    function clipboardd() {
        var copyText = document.getElementById("floatingTextarea2");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        Swal.fire(
            'Succès !',
            'Le texte est copié !',
            'success'
        )
    }
</script>
<script src="./libs/js/sa.js"></script>
<script src="./libs/js/bootstrap.bundle.min.js"></script>
</body>

</html>