<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
	redirect('index', false);
}

page_require_level(2);
$all_users = find_all_user();
$userr = $_GET['user'];
$eventt = $_GET['event'];
$role = $_GET['role'];
if (!$userr) {
	$session->msg("d", "Identifiant manquant.");
	redirect("eventadmin?nom=$eventt");
}
if (!$eventt) {
	$session->msg("d", "Evenement manquant.");
	redirect("eventadmin?nom=$eventt");
}
if (!$role) {
	$session->msg("d", "Role manquant.");
	redirect("eventadmin?nom=$eventt");
}
// cp --> Chef poste, xcp --> Sup Chef poste, chau --> chauffeur, xchau --> Sup Chef poste
?>


<?php
$resultatdemande = find_disponibilite_by_title($eventt, $userr);
if ($role == "cp") {
	$update_dispo = update_dispo_rolecp('oui', (int)$resultatdemande['id']);
	if ($update_dispo) {
		$session->msg("s", "Ajout role --> Chef Poste.");
		redirect("eventadmin?nom=$eventt");
	} else {
		$session->msg("d", "Erreur lors de l'ajout.");
		redirect("eventadmin?nom=$eventt");
	}
}
if ($role == "xcp") {
	$update_dispo = update_dispo_rolecp('non', (int)$resultatdemande['id']);
	if ($update_dispo) {
		$session->msg("s", "Suppression role --> Chef Poste.");
		redirect("eventadmin?nom=$eventt");
	} else {
		$session->msg("d", "Erreur lors de la suppression.");
		redirect("eventadmin?nom=$eventt");
	}
}

if ($role == "chau") {
	$update_dispo = update_dispo_rolechau('oui', (int)$resultatdemande['id']);
	if ($update_dispo) {
		$session->msg("s", "Ajout role --> Chauffeur.");
		redirect("eventadmin?nom=$eventt");
	} else {
		$session->msg("d", "Erreur lors de l'ajout.");
		redirect("eventadmin?nom=$eventt");
	}
}
if ($role == "xchau") {
	$update_dispo = update_dispo_rolechau('non', (int)$resultatdemande['id']);
	if ($update_dispo) {
		$session->msg("s", "Suppression role --> Chauffeur.");
		redirect("eventadmin?nom=$eventt");
	} else {
		$session->msg("d", "Erreur lors de la suppression.");
		redirect("eventadmin?nom=$eventt");
	}
}

?>
