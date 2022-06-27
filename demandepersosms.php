<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(2);
$all_users = find_all_user();
$users = join_user_table2();
require __DIR__ . '/vendor/autoload.php';

use \Ovh\Api;
?>


<?php
$eventt = $_GET['nom'];
if (!$eventt) {
  $session->msg("d", "Evenement manquant.");
  redirect("eventadmin?nom=$eventt");
}

$calendrier = find_event_by_title($eventt);
$titre3 = $calendrier['title'];
$titre2 = explode("DATE", $titre3);
$titre = $titre2[0];
$date2 = $calendrier['date'];
$date = date("d-m-Y", strtotime($date2));
$adresse = $calendrier['adresse'];
$start = $calendrier['start'];
$end = $calendrier['end'];

$body = 'MERCI D\'INDIQUER VOS DISPONIBILITES : $titre le $date de $p_timeeventstart à $p_timeeventend ADRESSE : $p_adresse';


$body2 = str_replace('$titre', $titre, $body);
$body3 = str_replace('$date', $date, $body2);
$body4 = str_replace('$p_timeeventstart', $start, $body3);
$body5 = str_replace('$p_timeeventend', $end, $body4);
$body6 = str_replace('$p_adresse', $adresse, $body5);

$endpoint = 'ovh-eu';
$applicationKey = "CHANGER-MOI";
$applicationSecret = "CHANGER-MOI";
$consumer_key = "CHANGER-MOI";

$conn = new Api(
  $applicationKey,
  $applicationSecret,
  $endpoint,
  $consumer_key
);

$smsServices = $conn->get('/sms');
foreach ($smsServices as $smsService) {

  print_r($smsService);
}
foreach ($users as $user) {
  $telephones[] = $user['telephone'];
}


$content = (object) array(
  "charset" => "UTF-8",
  "class" => "phoneDisplay",
  "coding" => "7bit",
  "message" => $body6,
  "receivers" => $telephones,
  "senderForResponse" => true,
  "validityPeriod" => 2880
);
$resultPostJob = $conn->post('/sms/' . $smsServices[0] . '/jobs', $content);

print_r($resultPostJob);

$smsJobs = $conn->get('/sms/' . $smsServices[0] . '/jobs');
print_r($smsJobs);
$session->msg("s", "SMS de demande a été envoyé avec succes.");
redirect("eventadmin?nom=$eventt");

?>
