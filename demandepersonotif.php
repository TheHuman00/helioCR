<?php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(2);
$all_users = find_all_user();
$users = join_user_table2();
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

$msg = array(
  'body'  => $titre . " le " . $date,
  'title'     => "⚠️ Besoin de personnel ⚠️",
  'vibrate'   => 1,
  'sound'     => 1,
  'badge'     => 1
);
$fields = array(
  'to'  => '/topics/allDevices',
  'notification'      => $msg
);

$headers = array(
  'Authorization: key=CHANGER-MOI',
  'Content-Type: application/json'
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
$result = curl_exec($ch);
curl_close($ch);
echo $result;
$session->msg("s", "Notif envoyé avec succes.");

?>
