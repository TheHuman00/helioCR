<?php
require_once('includes/load.php');
$evenements = join_calen_table();
$user = current_user(); 


foreach($evenements as $evenement) {
   if(exist_user_dispo($user['name'], $evenement["title"])){
      $couleurdispo = trouver_dispo_table($user['name'], $evenement["title"]);
      if($couleurdispo['dispo'] == "1"){
         $titre2 = $evenement["title"];
         $titre = explode("DATE", $titre2);
       $data[] = array(
        'id'   => $evenement["id"],
        'title'   => $titre[0],
        'start'   => $evenement["date"].'T'.$evenement['start'],
        'end'   => $evenement["date"].'T'.$evenement['end'],
         'color'   => 'orange'
       );
   
      }
   }
   if($evenement['start'] == null){
      $evenement['start'] = '00:01';
   }
   if($evenement['end'] == null){
      $evenement['end'] = '23:59';
   }
}

echo json_encode($data);
