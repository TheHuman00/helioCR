<?php
$page_title = 'Mots de passe';
require_once('includes/load.php');
page_require_level(1);
?>
<?php $user = current_user(); ?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
    <div class="container px-2">
        <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
            <div class="text-center mb-5">
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-bag-plus"></i></div>
                <h1 class="fw-bolder">Ajouter un produit</h1>
                <p class="lead fw-normal text-muted mb-0">Produit déjà existant ?</p>
            </div>
            <div class="row gx-5 justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <form method="POST" action="ajout_produit.php" name="productSearch" class="clearfix">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="productext" name="productname" type="text" placeholder="Nouveau produit" required />
                            <label for="produit">Nom du produit</label>
                        </div>
                        <div class="d-grid"><button class="btn btn-primary btn-lg" name="update" type="submit">Créer le produit</button></div>
                    </form>
                    <br>
                    <div id="suggestions">
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
<script src="./libs/js/jquery.min.js"></script>
<script>
    $(function() {
        $("#productext").keyup(function() {
            var productName = $(this).val();
            $.ajax({
                    method: "POST",
                    url: "getProduct.php",
                    data: {
                        product: productName
                    }
                })
                .done(function(data) {
                    $("#suggestions").show();
                    $("#suggestions").html(data);
                });
        });

    });

    function selectProduct(val) {
        $("#productext").val(val);
        $("#suggestions").hide();
    };
</script>
<script src="./libs/js/bootstrap.bundle.min.js"></script>
</body>

</html>