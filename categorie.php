<?php
$page_title = 'Catégorie';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
$database = new MySqli_DB();
page_require_level(1);
$user = current_user();
$all_categories = find_all('categories');

if (isset($_POST['add_cat'])) {
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));
  if (empty($errors)) {
    $sql  = $database->db_prepare("INSERT INTO categories (name)VALUES (?)");
    $sql->bind_param('s', $cat_name);
    if ($sql->execute()) {
      $session->msg("s", "Catégorie ajoutée avec succes");
      redirect('categorie', false);
    } else {
      $session->msg("d", "Echec de l'ajout");
      redirect('categorie', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('categorie', false);
  }
}

?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
  <div class="container px-2">
    <a style="color:#6c757d" href="./accueil-produit">
      <i class="bi bi-arrow-left"></i>
      Retour page accueil produits
    </a>
    <h1 class="fw-bolder fs-5 mb-4">Catégories :</h1>
    <div class="card border-0 shadow rounded-3 overflow-hidden">
      <div class="card-body p-0">
        <div class="row gx-0">
          <div class="p-2 p-md-2">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="col">#</th>
                  <th class="col">Catégorie</th>
                  <th class="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($all_categories as $cat) : ?>

                  <tr>
                    <td><?php echo count_id(); ?></td>
                    <td><?php echo remove_junk(ucwords($cat['name'])); ?></td>
                    <td>
                      <div class="btn-group">
                        <a href="edit-cat?id=<?php echo (int)$cat['id']; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Editer">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <!-- <a href="sup_categorie.php?id=<?php echo (int)$cat['id']; ?>" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Supprimer">
                                        <i class="bi bi-x-lg"></i>
                                      </a> -->
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="bg-light py-5">
  <div class="container px-2">
    <div class="rounded-3 py-5 px-4 px-md-5 mb-5">
      <div class="row gx-5 justify-content-center">
        <div class="col-lg-8 col-xl-6">
          <form action="categorie.php" method="post" class="clearfix">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="categorie-name" placeholder="Nom de la catégorie" required>
              <label for="name">Nom de la catégorie</label>
            </div>
            <div class="d-grid"><button class="btn btn-primary btn-lg" name="add_cat" type="submit">Ajouter catégorie</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include_once('layouts/footer.php'); ?>