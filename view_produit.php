<?php
$page_title = 'Volontaires';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(1);
$user = current_user();
$products = join_product_table();
$date = make_date();
foreach ($products as $product) {
  if ($product['id'] == $_GET['id']) {
    $name = $product['name'];
    $categorie = $product['categorie'];
    $date_per = $product['date_peremp'];
    $quanty = $product['quantity'];
    $utilisation = $product['utili'];
    $contreindi = $product['ci'];
  }
}



?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5 bg-light">
  <div class="container px-5">
    <div class="row gx-5">
      <div class="col-xl-8">
        <a style="color:#6c757d" href="./produit">
          <i class="bi bi-arrow-left"></i>
          Retour page produits
        </a>
        <h1 class="fw-bolder"><?php echo $name ?></h1>
        <br>
        <div class="card shadow border-0">
          <div class="card-body">
            <a class="text-decoration-none link-dark stretched-link text-center" href="#!">
              <h5 class="card-title mb-3">Utilisation :</h5>
            </a>
            <?php if ($utilisation == NULL || $utilisation == "") : ?>
              <p class="card-text mb-0 text-center">---</p>
            <?php else : ?>
              <p class="card-text mb-0 text-center"><?php echo $utilisation ?></p>
            <?php endif; ?>
          </div>
        </div>
        <br>
        <div class="card shadow border-0">
          <div class="card-body">
            <a class="text-decoration-none link-dark stretched-link text-center" href="#!">
              <h5 class="card-title mb-3">Contre-indication :</h5>
            </a>
            <?php if ($contreindi == NULL || $contreindi == "") : ?>
              <p class="card-text mb-0 text-center">---</p>
            <?php else : ?>
              <p class="card-text mb-0 text-center"><?php echo $contreindi ?></p>
            <?php endif; ?>
          </div>
        </div>
        <br>


      </div>
      <div class="col-xl-4">
        <div class="card border-0 h-100">
          <div class="card-body p-4">
            <div class="d-flex h-100 align-items-center justify-content-center">
              <div class="text-center">
                <div class="h6 fw-bolder">Où cela se trouve ?</div>
                <p><?php echo $categorie ?></p>
                <div class="h6 fw-bolder">Date de péremption</div>
                <?php if (empty($date_per)) : ?>
                  <p style="color:#198754">---</p>
                <?php else : ?>
                  <?php if ($date_per < $date) : ?>
                    <p style="background-color: red;"><?php echo $date_per ?></p>
                  <?php else : ?>
                    <p style="color:#198754"><?php echo $date_per ?></p>
                  <?php endif; ?>
                <?php endif; ?>
                <div class="h6 fw-bolder">Quantité</div>
                <?php if ($quanty <= 0) : ?>
                  <p style="background-color: red;"><?php echo $quanty ?></p>
                <?php else : ?>
                  <p style="color:#198754"><?php echo $quanty ?></p>
                <?php endif; ?>
                <a href="edit_produit?id=<?php echo (int)$_GET['id']; ?>" class="btn btn-m btn-warning" data-toggle="tooltip" title="Editer">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="sup_produit?id=<?php echo (int)$_GET['id']; ?>" class="btn btn-m btn-danger" data-toggle="tooltip" title="Supprimer">
                  <i class="bi bi-x-lg"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="py-5">
  <div class="container px-2">
    <h1 class="fw-bolder fs-5 mb-4">Autres emplacements :</h1>
    <div class="card border-0 shadow rounded-3 overflow-hidden">
      <div class="card-body p-0">
        <div class="row gx-0">
          <div class="p-2 p-md-2">
            <div class="autre">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="col">#</th>
                    <th class="col">Catégorie</th>
                    <th class="col">Quantité</th>
                    <th class="col">Date de péremption</th>
                    <th class="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $product) :
                    if ($product['name'] == $name) :
                      if ($product['date_peremp'] == $date_per && $product['categorie'] == $categorie) :
                      else : ?>


                        <tr>
                          <td><?php echo count_id(); ?></td>

                          <td><?php echo remove_junk(ucwords($product['categorie'])); ?></td>

                          <?php if ($product['quantity'] <= 0) : ?>
                            <td style="background-color: red;"><?php echo remove_junk(ucwords($product['quantity'])); ?></td>
                          <?php else : ?>
                            <td><?php echo remove_junk(ucwords($product['quantity'])); ?></td>
                          <?php endif; ?>

                          <?php if (empty($product['date_peremp'])) : ?>
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
                              <!-- <a href="sup_produit?id=<?php echo (int)$product['id']; ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Supprimer">
                                        <i class="bi bi-x-lg"></i>
                                      </a> -->
                            </div>
                          </td>
                        </tr>
                  <?php endif;
                    endif;
                  endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="phone">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="col">Catégorie</th>
                    <th class="col">Date de pér.</th>
                    <th class="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $product) :
                    if ($product['name'] == $name) :
                      if ($product['date_peremp'] == $date_per && $product['categorie'] == $categorie) :
                      else : ?>


                        <tr>

                          <td><?php echo remove_junk(ucwords($product['categorie'])); ?></td>

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
                    endif;
                  endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>



</section>
<?php include_once('layouts/footer.php'); ?>