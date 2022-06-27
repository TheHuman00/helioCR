<?php
require_once('includes/load.php');
page_require_level(1);
?>
<?php
$product = find_by_id('products', (int)$_GET['id']);
if (!$product) {
  $session->msg("d", "Identifiant de produit manquant.");
  redirect('produit');
}
?>
<?php
$delete_id = delete_by_id('products', (int)$product['id']);
if ($delete_id) {
  $session->msg("s", "Produits supprimés.");
  redirect('produit');
} else {
  $session->msg("d", "La suppression des produits a échoué.");
  redirect('produit');
}
?>
