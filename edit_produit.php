<?php
$page_title = 'Ajouter produit';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(1);
$user = current_user();
$all_categories = find_all('categories');
$product = find_by_id('products', (int)$_GET['id']);
if (!$product) {
  $session->msg("d", 'Pas de ID produit');
  redirect('produit');
}


if (isset($_POST['add_product'])) {
  if (empty($errors)) {
    $p_buy   = remove_junk($db->escape($_POST['date-peremp']));
    $p_name  = remove_junk($db->escape($_POST['product-title']));
    $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
    $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
    $p_utili = remove_junk($db->escape($_POST['utilisation']));
    $p_ci    = remove_junk($db->escape($_POST['contre-indic']));
    $query   = "UPDATE products SET";
    $query  .= " name ='{$p_name}', quantity ='{$p_qty}',";
    $query  .= " date_peremp ='{$p_buy}', categorie_id ='{$p_cat}',utili='{$p_utili}', ci='{$p_ci}'";
    $query  .= " WHERE id ='{$product['id']}'";
    $result = $db->query($query);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Produit mis à jour ");
      redirect('produit', false);
    } else {
      $session->msg('d', ' Désolé, échec de la mise à jour!');
      redirect('produit', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('produit', false);
  }
}

?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
  <div class="container px-2">
    <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
      <div class="text-center mb-5">
        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-droplet"></i></div>
        <h1 class="fw-bolder">Ajouter un produit</h1>
        <p class="lead fw-normal text-muted mb-0">Un nouveau produit ?</p>
      </div>
      <div class="row gx-5 justify-content-center">
        <div class="col-lg-8 col-xl-6">
          <form method="post" action="edit_produit.php?id=<?php echo (int)$product['id'] ?>">
            <div class="form-floating mb-3">
              <input class="form-control" name="product-title" type="text" placeholder="Nom du produit" value="<?php echo remove_junk($product['name']); ?>" required />
              <label for="name">Nom du produit</label>
            </div>
            <div class="form-floating mb-3">
              <input class="form-control" name="date-peremp" type="mouth" placeholder="Date de péremption" value="<?php echo remove_junk($product['date_peremp']); ?>" required />
              <label for="phone">Date de péremption</label>
            </div>
            <div class="form-floating mb-3">
              <input class="form-control" name="product-quantity" type="number" placeholder="Quantité du produit" value="<?php echo remove_junk($product['quantity']); ?>" required />
              <label for="email">Quantité du produit</label>
            </div>
            <div class="form-floating mb-3">
              <input class="form-control" name="utilisation" type="text" placeholder="Utilisation" value="<?php echo remove_junk($product['utili']); ?>" />
              <label for="email">Utilisation</label>
            </div>
            <div class="form-floating mb-3">
              <input class="form-control" name="contre-indic" type="text" placeholder="Contre indication" value="<?php echo remove_junk($product['ci']); ?>" />
              <label for="email">Contre indication</label>
            </div>
            <select class="form-select" name="product-categorie" required>
              <option selected>Sélectionner où se trouve le produit</option>
              <?php foreach ($all_categories as $cat) : ?>
                <option value="<?php echo (int)$cat['id'] ?>" <?php if ($product['categorie_id'] === $cat['id']) : echo "selected";
                                                              endif; ?>>
                  <?php echo $cat['name'] ?></option>
              <?php endforeach; ?>
            </select>
            <br>
            <br>
            <div class="d-grid"><button class="btn btn-primary btn-lg" name="add_product" type="submit">Mettre à jour le produit</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>











<?php include_once('layouts/footer.php'); ?>