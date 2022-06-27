<?php
require_once('includes/load.php');
page_require_level(2);
$event = $_GET['event'];
$user = $_GET['user'];
?>
<?php
$change = change_hist_statut($event, $user);
if ($change) {
  $session->msg("s", "Marqué comme LU.");
  redirect("eventadmin?nom=$event");
} else {
  $session->msg("d", "ERREUR.");
  redirect("eventadmin?nom=$event");
}
?>
