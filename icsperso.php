<?php 
require_once('includes/load.php');
$evenements = join_calen_table();

$e_user = find_by_id('users',(int)$_GET['id']);
if(!$e_user){
  $session->msg("d","Pas d'user ID.");
  redirect('index');
}

$user = $e_user['name'];
$disponibilites = join_dispo_table();

header('Content-Type: text/plain; charset=utf-8');
$icsFile = "BEGIN:VCALENDAR\r\n";
$icsFile .= "VERSION:2.0\r\n";
$icsFile .= "PRODID:-//ZContent.net//ZapCalLib 1.0//EN\r\n";
$icsFile .= "X-WR-CALNAME:Préventif Croix-Rouge\r\n";
$icsFile .= "X-MS-OLK-FORCEINSPECTOROPEN:TRUE\r\n";
$icsFile .= "X-WR-TIMEZONE:Europe/Brussels\r\n";
$icsFile .= "BEGIN:VTIMEZONE\r\n";
$icsFile .= "TZID:Europe/Brussels\r\n";
$icsFile .= "END:VTIMEZONE\r\n";
$icsFile .= "CALSCALE:GREGORIAN\r\n";
$icsFile .= "METHOD:PUBLISH\r\n";

foreach($evenements as $event){
    if($event['description'] == "---"){
        $description = false;
    }else{
        $description = true;
    }
    if($event['adresse'] == "---"){
        $adresse = false;
    }else{
        $adresse = true;
    }
    $titre = explode("DATE", $event['title']); 
    $orgDatestart = $event['date']." ".$event['start'].":00";  
    $newDatestart = date("Ymd\THis", strtotime($orgDatestart));  
    $orgDateend = $event['date']." ".$event['end'].":00";  

    if($event['end'] < $event['start']){
        $newDateend = date("Ymd\THis", strtotime("+1 day", strtotime($orgDateend))); 
    }else{
        $newDateend = date("Ymd\THis", strtotime($orgDateend)); 
    }
    foreach($disponibilites as $dispo){
        if($dispo['user'] == $user){
            if($dispo['event'] == $event['title']){
                if($dispo['dispo'] == "1"){
                    $icsFile .= "BEGIN:VEVENT\r\n";
                    $icsFile .= "SUMMARY;LANGUAGE=fr-be:" . $titre[0] . " EN ATTENTE" . "\r\n";
                    $icsFile .= "DTSTART:" . $newDatestart."\r\n";
                    $icsFile .= "DTEND:" . $newDateend."\r\n";
                    $icsFile .= "ORGANIZER:Croix-Rouge-Ixelles\r\n";
                    $icsFile .= "UID:" . $event['id']."@croix-rouge-ixelles.com\r\n";
                    $icsFile .= "DTSTAMP:" . date("Ymd\THis\Z")."\r\n";
                    if($adresse == true){
                        $icsFile .= "LOCATION:" . $event['adresse']."\r\n";
                    }
                    $icsFile .= "END:VEVENT\r\n"; 
                }
                if($dispo['dispo'] == "2"){
                    $icsFile .= "BEGIN:VEVENT\r\n";
                    $icsFile .= "SUMMARY;LANGUAGE=fr-be:" . $titre[0] . " CONFIRMÉ" . "\r\n";
                    $icsFile .= "DTSTART:" . $newDatestart."\r\n";
                    $icsFile .= "DTEND:" . $newDateend."\r\n";
                    $icsFile .= "ORGANIZER:Croix-Rouge-Ixelles\r\n";
                    $icsFile .= "UID:" . $event['id']."@croix-rouge-ixelles.com\r\n";
                    $icsFile .= "DTSTAMP:" . date("Ymd\THis\Z")."\r\n";
                    if($adresse == true){
                        $icsFile .= "LOCATION:" . $event['adresse']."\r\n";
                    }
                    $icsFile .= "END:VEVENT\r\n"; 
                }
                
            }
        }
    }
}
$icsFile .= "END:VCALENDAR";

echo $icsFile;
