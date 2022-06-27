<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(2);
?>
<?php
$eventt = $_GET['nom'];
if (!$eventt) {
  $session->msg("d", "Evenement manquant.");
  redirect("index");
}
?>
<?php
$delete_action = delete_by_title('calendrier', $eventt);
if ($delete_action) {
  $delete_action2 = delete_by_event('disponibilite', $eventt);
  if ($delete_action2) {
    $delete_action3 = delete_by_historique('historique', $eventt);
    if ($delete_action3) {
      $session->msg("s", "Supprimé avec succes.");
      redirect("index");
    } else {
      $session->msg("d", "Erreur de suppression.");
      redirect("index");
    }
  } else {
    $session->msg("d", "Erreur de suppression.");
    redirect("index");
  }
} else {
  $session->msg("d", "Erreur de suppression.");
  redirect("index");
}
?>
