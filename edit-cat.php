<?php
$page_title = 'Modifier catégorie';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(1);
$user = current_user();
$all_categories = find_all('categories');

$categorie = find_by_id('categories', (int)$_GET['id']);
if (!$categorie) {
  $session->msg("d", "Identifiant de catégorie manquant.");
  redirect('categorie');
}

if (isset($_POST['edit_cat'])) {
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));
  if (empty($errors)) {
    $sql = "UPDATE categories SET name='{$cat_name}'";
    $sql .= " WHERE id='{$categorie['id']}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      $session->msg("s", "Catégorie mise à jour avec succès");
      redirect('categorie', false);
    } else {
      $session->msg("d", "Échec de mise à jour");
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
</section>
<section class="py-5">
  <div class="container px-2">
    <div class="rounded-3 py-5 px-4 px-md-5 mb-5">
      <div class="row gx-5 justify-content-center">
        <div class="col-lg-8 col-xl-6">
          <form action="edit-cat.php?id=<?php echo (int)$categorie['id']; ?>" method="post" class="clearfix">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="categorie-name" value="<?php echo remove_junk(ucfirst($categorie['name'])); ?>" required>
              <label for="name">Nom de la catégorie</label>
            </div>
            <div class="d-grid"><button class="btn btn-primary btn-lg" name="edit_cat" type="submit">Mettre à jour la catégorie</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include_once('layouts/footer.php'); ?>