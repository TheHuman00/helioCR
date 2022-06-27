<?php
require_once('includes/load.php');
$historiques = join_hist_table();


foreach($historiques as $historique) {
    if($historique['dispostatus'] == '1'){
    $titre2 = $historique["event"];
    $titre = explode("DATE", $titre2);
    $orgDate = $titre[1];
    $newDate = date("d-m-Y", strtotime($orgDate));
    $data[] = array(
     'id'   => $historique["id"],
     'title'   => $historique['user']." | DISPONIBLE | ".$titre[0]." | ".$newDate,
     'start'   => $historique["date"],
      'color'   => 'orange'
    );
    }
    if($historique['dispostatus'] == '0'){
        $titre2 = $historique["event"];
        $titre = explode("DATE", $titre2);
        $orgDate = $titre[1];
        $newDate = date("d-m-Y", strtotime($orgDate)); 
        $data[] = array(
        'id'   => $historique["id"],
        'title'   => $historique['user']." | INDISPONIBLE | ".$titre[0]." | ".$newDate,
        'start'   => $historique["date"],
        'color'   => 'red'
        );
    }

}

echo json_encode($data);
