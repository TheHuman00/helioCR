<?php
$page_title = 'Produits';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(1);
$user = current_user();
$all_users = find_all_user();
$products = join_product_table();
$all_categories = find_all('categories');
$date = make_date();

page_require_level(1);

?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<script type="text/javascript" src="libs/js/jquery.min.js"></script>
<script type="text/javascript" src="libs/js/jquery.maphilight.min.js"></script>
<script>
  $(function() {
    $('.mapq').maphilight();
  });
</script>
<section class="py-5">
  <div class="container px-2">
    <a style="color:#6c757d" href="./accueil-produit">
      <i class="bi bi-arrow-left"></i>
      Retour page accueil produits
    </a>
    <h1 class="fw-bolder fs-5 mb-4">Choisir emplacement :</h1>
    <img src="libs/img/medoc.svg" width="720" height="319" border="0" usemap="#map" class="mapq" />

    <map name="map">
      <!-- #$-:Image map file created by GIMP Image Map plug-in -->
      <!-- #$-:GIMP Image Map plug-in by Maurits Rijk -->
      <!-- #$VERSION:2.3 -->
      <!-- #$AUTHOR:Guillaume Boen -->
      <area shape="rect" coords="3,4,72,97" alt="1" href="produit-cat-medoc?cat=medicalise1-1" />
      <area shape="rect" coords="75,4,144,97" alt="2" href="produit-cat-medoc?cat=medicalise1-2" />
      <area shape="rect" coords="157,3,227,99" alt="3" href="produit-cat-medoc?cat=medicalise1-3" />
      <area shape="rect" coords="229,4,298,98" alt="4" href="produit-cat-medoc?cat=medicalise1-4" />
      <area shape="rect" coords="420,3,490,98" alt="5" href="produit-cat-medoc?cat=medicalise1-5" />
      <area shape="rect" coords="493,4,562,98" alt="6" href="produit-cat-medoc?cat=medicalise1-6" />
      <area shape="rect" coords="576,3,645,98" alt="7" href="produit-cat-medoc?cat=medicalise1-7" />
      <area shape="rect" coords="647,4,717,98" alt="8" href="produit-cat-medoc?cat=medicalise1-8" />
      <area shape="rect" coords="3,112,103,205" alt="9" href="produit-cat-medoc?cat=medicalise1-9" />
      <area shape="rect" coords="105,113,191,205" alt="10" href="produit-cat-medoc?cat=medicalise1-10" />
      <area shape="rect" coords="193,112,299,206" alt="11" href="produit-cat-medoc?cat=medicalise1-11" />
      <area shape="rect" coords="419,112,521,206" alt="12" href="produit-cat-medoc?cat=medicalise1-12" />
      <area shape="rect" coords="522,112,623,206" alt="13" href="produit-cat-medoc?cat=medicalise1-13" />
      <area shape="rect" coords="623,111,718,208" alt="14" href="produit-cat-medoc?cat=medicalise1-14" />
      <area shape="rect" coords="2,218,144,314" alt="15" href="produit-cat-medoc?cat=medicalise1-15" />
      <area shape="rect" coords="157,219,227,312" alt="16" href="produit-cat-medoc?cat=medicalise1-16" />
      <area shape="rect" coords="229,218,299,313" alt="17" href="produit-cat-medoc?cat=medicalise1-17" />
      <area shape="rect" coords="420,219,562,265" alt="18" href="produit-cat-medoc?cat=medicalise1-18" />
      <area shape="rect" coords="420,268,562,312" alt="19" href="produit-cat-medoc?cat=medicalise1-19" />
      <area shape="rect" coords="575,218,646,313" alt="20" href="produit-cat-medoc?cat=medicalise1-20" />
      <area shape="rect" coords="646,218,717,313" alt="21" href="produit-cat-medoc?cat=medicalise1-21" />
      <area shape="rect" coords="312,4,406,313" alt="22" href="produit-cat-medoc?cat=medicalise1-22" />
    </map>


  </div>

  </div>
</section>
<?php include_once('layouts/footer.php'); ?>