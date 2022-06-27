<?php $page_title = "Credits";
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
    redirect('index', false);
};
$user = current_user();
include_once('./layouts/header.php'); ?>
<section class="text-center my-5">
    <h1 class="fw-bolder mb-3">Credits</h1>
    <div class="container px-5">
        <h2><a style="color:black;" href="https://www.php.net/">PHP</a>
            <br>
            <a style="color:black;" href="https://fullcalendar.io/">FullCalendar</a>
            <br>
            <a style="color:black;" href="https://www.mysql.com/">MySQL</a>
            <br>
            <a style="color:black;" href="https://www.javascript.com/">JavaScript</a>
            <br>
            <a style="color:black;" href="https://getbootstrap.com/">GetBootStrap</a>
            <br>
            <a style="color:black;" href="https://startbootstrap.com/">StartBootStrap</a>
        </h2>
        <br>
    </div>
</section>
<section class="py-5 bg-light">
    <div class="container px-5 my-5">
        <div class="row gx-5 row-cols-1 row-cols-sm-2 row-cols-xl-4 justify-content-center">
            <div class="col mb-5 mb-5 mb-xl-0">
                <div class="text-center">
                    <img class="img-fluid rounded-circle mb-4 px-4" src="./libs/img/moi.jpg" alt="..." width="200" height="200" />
                    <h5 class="fw-bolder">Guillaume Boen</h5>
                    <div class="fst-italic text-muted">On est là hn...</div>
                </div>
            </div>
        </div>
    </div>
</section>

</main>

<?php include_once('./layouts/footer.php'); ?>