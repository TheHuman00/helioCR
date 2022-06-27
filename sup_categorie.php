<?php
require_once('includes/load.php');

page_require_level(1);
?>
<?php
$categorie = find_by_id('categories', (int)$_GET['id']);
if (!$categorie) {
  $session->msg("d", "Identifiant de catégorie manquant.");
  redirect('categorie');
}
?>
<?php
$delete_id = delete_by_id('categories', (int)$categorie['id']);
if ($delete_id) {
  $session->msg("s", "Catégorie supprimée.");
  redirect('categorie');
} else {
  $session->msg("d", "Échec de la suppression de la catégorie.");
  redirect('categorie');
}
?>
