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
<section class="py-5">
  <div class="autre">
    <div class="container px-2">
      <a style="color:#6c757d" href="./pre-produit-cat">
        <i class="bi bi-arrow-left"></i>
        Retour schéma principal
      </a>
      <br>
      <br>
      <h1 class="fw-bolder fs-5 mb-4">Produits <?php echo $_GET["cat"] ?>:</h1>
      <div class="card border-0 shadow rounded-3 overflow-hidden">
        <div class="card-body p-0">
          <div class="row gx-0">
            <div class="p-2 p-md-2">
              <div class="col">
                <a href="pre_produit" class="btn btn-primary">Ajouter un produit</a>
                </br>
                </br>
              </div>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="col">#</th>
                    <th class="col">Nom du produit</th>
                    <th class="col">Catégorie</th>
                    <th class="col">Quantité</th>
                    <th class="col">Date de péremption</th>
                    <th class="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $product) :
                    if ($_GET['cat'] == $product['categorie']) : ?>

                      <tr>
                        <td><?php echo count_id(); ?></td>
                        <td><?php echo remove_junk(ucwords($product['name'])); ?></td>

                        <td><?php echo remove_junk(ucwords($product['categorie'])); ?></td>

                        <?php if ($product['quantity'] <= 0) : ?>
                          <td style="background-color: red;"><?php echo remove_junk(ucwords($product['quantity'])); ?></td>
                        <?php else : ?>
                          <td><?php echo remove_junk(ucwords($product['quantity'])); ?></td>
                        <?php endif; ?>


                        <?php if ($product['date_peremp'] == "---") : ?>
                          <td>---</td>
                        <?php else : ?>
                          <?php if ($product['date_peremp'] < $date) : ?>
                            <td style="background-color: red;"><?php echo remove_junk(ucwords($product['date_peremp'])); ?></td>
                          <?php else : ?>
                            <td><?php echo remove_junk(ucwords($product['date_peremp'])); ?></td>
                          <?php endif; ?>
                        <?php endif; ?>


                        <td class="text-center">
                          <div class="btn-group">
                            <a href="view_produit?id=<?php echo (int)$product['id']; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Voir">
                              <i class="bi bi-eye"></i>
                            </a>
                            <a href="edit_produit?id=<?php echo (int)$product['id']; ?>" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Editer">
                              <i class="bi bi-pencil"></i>
                            </a>
                          </div>
                        </td>
                      </tr>
                  <?php endif;
                  endforeach; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
  <div class="phone">
    <div class="container px-2">
      <a style="color:#6c757d" href="./pre-produit-cat">
        <i class="bi bi-arrow-left"></i>
        Retour schéma
      </a>
      <h1 class="fw-bolder fs-5 mb-4">Produits <?php echo $_GET['cat'] ?></h1>
      <div class="card border-0 shadow rounded-3 overflow-hidden">
        <div class="card-body p-0">
          <div class="row gx-0">
            <div class="p-2 p-md-2">
              <div class="py-1">
                <a href="pre_produit" class="btn btn-primary">Ajouter un produit</a>
              </div>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="col">Nom du produit</th>
                    <th class="col">Quantié</th>
                    <th class="col">Date de pér.</th>
                    <th class="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $product) :
                    if ($_GET['cat'] == $product['categorie']) : ?>

                      <tr>
                        <td><?php echo remove_junk(ucwords($product['name'])); ?></td>
                        <?php if ($product['quantity'] <= 0) : ?>
                          <td style="background-color: red;"><?php echo remove_junk(ucwords($product['quantity'])); ?></td>
                        <?php else : ?>
                          <td><?php echo remove_junk(ucwords($product['quantity'])); ?></td>
                        <?php endif; ?>


                        <?php if ($product['date_peremp'] == NULL) : ?>
                          <td>
                          <td>
                          <?php else : ?>
                            <?php if ($product['date_peremp'] < $date) : ?>
                          <td style="background-color: red;"><?php echo remove_junk(ucwords($product['date_peremp'])); ?></td>
                        <?php else : ?>
                          <td><?php echo remove_junk(ucwords($product['date_peremp'])); ?></td>
                        <?php endif; ?>
                      <?php endif; ?>


                      <td class="text-center">
                        <div class="btn-group">
                          <a href="view_produit?id=<?php echo (int)$product['id']; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Voir">
                            <i class="bi bi-eye"></i>
                          </a>
                          <a href="edit_produit?id=<?php echo (int)$product['id']; ?>" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Editer">
                            <i class="bi bi-pencil"></i>
                          </a>
                        </div>
                      </td>
                      </tr>
                  <?php endif;
                  endforeach; ?>
                </tbody>
              </table>

            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include_once('layouts/footer.php'); ?>