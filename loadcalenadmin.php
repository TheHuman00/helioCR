<?php
require_once('includes/load.php');
$evenements = join_calen_table();
$user = current_user();
$all_historique = find_all('historique');
$disponibilites = find_all('disponibilite');


foreach ($evenements as $evenement) {
   if (exist_user_dispo($user['name'], $evenement["title"])) {
      $couleurdispo = trouver_dispo_table($user['name'], $evenement["title"]);
      if ($couleurdispo['dispo'] == "0") {
         $coleurdevent = "red";
      }
      if ($couleurdispo['dispo'] == "1") {
         $coleurdevent = "orange";
      }
      if ($couleurdispo['dispo'] == "2") {
         $coleurdevent = "green";
      }
   } else {
      $coleurdevent = "blue";
   }
   if ($evenement['start'] == null) {
      $evenement['start'] = '00:01';
   }
   if ($evenement['end'] == null) {
      $evenement['end'] = '23:59';
   }
   $titre2 = $evenement["title"];
   $titre = explode("DATE", $titre2);
   $data[] = array(
      'id'   => $evenement["id"],
      'title'   => $titre[0],
      'start'   => $evenement["date"] . 'T' . $evenement['start'],
      'end'   => $evenement["date"] . 'T' . $evenement['end'],
      'color'   => $coleurdevent
   );
}
foreach ($all_historique as $historique) {
   if ($historique['lu'] == "0") {
      if ($historique['dispostatus'] == "1" || $historique['dispostatus'] == "0") {
         $titre2 = $historique["event"];
         $titre = explode("DATE", $titre2);
         $date = $titre[1];
         $data[] = array(
            'start'   => $date,
            'end'   => $date,
            'display' => 'background'
         );
      }
   }
}

echo json_encode($data);
