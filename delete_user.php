<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(1);
?>
<?php
$delete_id = delete_by_id('users', (int)$_GET['id']);
if ($delete_id) {
  $session->msg("s", "Utilisateur supprimé.");
  redirect('utilisateuradmin');
} else {
  $session->msg("d", "Échec de la suppression de l'utilisateur ou permission manquant.");
  redirect('utilisateuradmin');
}
?>
