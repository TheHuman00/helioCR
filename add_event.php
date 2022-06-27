<?php
$page_title = 'Ajouter prev';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
$users = join_user_table2();
$user = current_user();
page_require_level(2);
$evenements = join_calen_table();

$database = new MySqli_DB();
$dateevent = $_GET['date'];
if (!$dateevent) {
  $session->msg("d", "Date manquante.");
  redirect('index');
}
require __DIR__ . '/vendor/autoload.php';

use \Ovh\Api;

$endpoint = 'ovh-eu';
$applicationKey = "CHANGER-MOI"; // Application key OVH
$applicationSecret = "CHANGER-MOI"; // Application secret OVH
$consumer_key = "CHANGER-MOI"; // Consumer key OVH

$conn = new Api(
  $applicationKey,
  $applicationSecret,
  $endpoint,
  $consumer_key
);

$resultsms = $conn->get('/sms/rates/destinations', array(
  'billingCountry' => 'fr',
  'country' => 'be',
));
$pricesms2 = $resultsms['price'];
$valuesms = $pricesms2['value'];
$count2 = 0;
foreach ($users as $userrr) {
  $count2 = $count2 + 1;
}
$pricesmspasarrondi = $count2 * $pricesms2['value'];
$pricesms = round($pricesmspasarrondi, 2);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
?>
<?php
if (isset($_POST['add_event'])) {
  if (empty($errors)) {
    $titretrim2 = trim($_POST['titleevent'], "'");
    $titretrim = trim($titretrim2, "\"");
    $titrefinal = $titretrim . 'DATE' . $dateevent;
    $p_titleevent   = remove_junk($db->escape($titrefinal));
    $p_timeeventstart  = remove_junk($db->escape($_POST['timeeventstart']));
    $p_timeeventend   = remove_junk($db->escape($_POST['timeeventend']));
    $p_dateevent   = remove_junk($db->escape($_GET['date']));
    if ($_POST['doubleprev'] == "oui") {
      $p_doubleprev   = remove_junk($db->escape($_POST['doubleprev']));
    } else {
      $p_doubleprev   = remove_junk($db->escape("non"));
    }
    if ($_POST['description'] !== "") {
      $p_description  = remove_junk($db->escape($_POST['description']));
    } else {
      $p_description = "---";
    }
    if ($_POST['adresse'] !== "") {
      $p_adresse  = remove_junk($db->escape($_POST['adresse']));
    } else {
      $p_adresse = "---";
    }
    if ($_POST['personnel'] !== "") {
      $p_personnel  = remove_junk($db->escape($_POST['personnel']));
    } else {
      $p_personnel = "0";
    }
    $query = "INSERT INTO calendrier ( title,start,end,date,description,adresse,personnel,doubleprev) VALUES ('{$p_titleevent}', '{$p_timeeventstart}', '{$p_timeeventend}', '{$p_dateevent}', '{$p_description}', '{$p_adresse}', '{$p_personnel}', '{$p_doubleprev}')";
    if ($db->query($query)) {
      $calendrier = find_event_by_title($eventt);
      if ($_POST['doubleprev'] == "oui") {
        foreach ($evenements as $evenement) {
          if ($evenement['date'] == $_GET['date']) {
            if ($evenement['title'] !== $calendrier['title']) {
              $sql = $database->db_prepare("UPDATE calendrier SET doubleprev = 'oui' WHERE title= ?");
              $sql->bind_param("s", $evenement['title']);
              if ($sql->execute()) {
                $session->msg('s', "Evénement ajouté ");
              }
            }
          }
        }
      }

      if ($_POST['email'] == "ok") {
        $urlredirect = "https://croix-rouge-ixelles.com/eventuser?nom=$titrefinal";

        $titre = $_POST['titleevent'];
        $date2 = $p_dateevent;
        $date = date("d-m-Y", strtotime($date2));
        $adresse = $p_adresse;
        $start = $p_timeeventstart;
        $end = $p_timeeventend;
        $description = $p_description;

        $body = '<html>
        <head>
          <meta http-equiv="content-type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0;">
           <meta name="format-detection" content="telephone=no"/>
        
          <style>
        body { margin: 0; padding: 0; min-width: 100%; width: 100% !important; height: 100% !important;}
        body, table, td, div, p, a { -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse !important; border-spacing: 0; }
        img { border: 0; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        #outlook a { padding: 0; }
        .ReadMsgBody { width: 100%; } .ExternalClass { width: 100%; }
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
        @media all and (min-width: 560px) {
          .container { border-radius: 8px; -webkit-border-radius: 8px; -moz-border-radius: 8px; -khtml-border-radius: 8px;}
        }
        a, a:hover {
          color: #127DB3;
        }
        .footer a, .footer a:hover {
          color: #999999;
        }
        
           </style>
          <title>$titre</title>
        
        </head>
        <body topmargin="0" rightmargin="0" bottommargin="0" leftmargin="0" marginwidth="0" marginheight="0" width="100%" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%; height: 100%; -webkit-font-smoothing: antialiased; text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; line-height: 100%;
          background-color: #F0F0F0;
          color: #000000;"
          bgcolor="#F0F0F0"
          text="#000000">
        <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; width: 100%;" class="background"><tr><td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;"
          bgcolor="#F0F0F0">
        <table border="0" cellpadding="0" cellspacing="0" align="center"
          width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
          max-width: 560px;" class="wrapper">
          <tr>
            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
              padding-top: 20px;
              padding-bottom: 20px;">
              <a target="_blank" style="text-decoration: none;"
                href="https://croix-rouge-ixelles.com"><img border="0" vspace="0" hspace="0"
                src="https://croix-rouge-ixelles.com/libs/img/helio-rouge.png"
                width="100" height="100" style="
                color: #000000;
                font-size: 10px; margin: 0; padding: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;" /></a>
            </td>
          </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" align="center"
          bgcolor="#FFFFFF"
          width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
          max-width: 560px;" class="container">
          <tr>
            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 24px; font-weight: bold; line-height: 130%;
              padding-top: 25px;
              color: #000000;
              font-family: sans-serif;" class="header">
                Préventif ajouté : $titre 
            </td>
          </tr>
          <tr>
            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-bottom: 3px; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 18px; font-weight: 300; line-height: 150%;
              padding-top: 5px;
              color: #000000;
              font-family: sans-serif;" class="subheader">
                Le $date
            </td>
          </tr>
          <tr>
            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
              padding-top: 25px;
              padding-bottom: 5px;" class="button"><a
              href="$urlredirect" target="_blank" style="text-decoration: underline;">
                <table border="0" cellpadding="0" cellspacing="0" align="center" style="max-width: 240px; min-width: 120px; border-collapse: collapse; border-spacing: 0; padding: 0;"><tr><td align="center" valign="middle" style="padding: 12px 24px; margin: 0; text-decoration: underline; border-collapse: collapse; border-spacing: 0; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -khtml-border-radius: 4px;"
                  bgcolor="#E74C3C"><a target="_blank" style="text-decoration: underline;
                  color: #FFFFFF; font-family: sans-serif; font-size: 17px; font-weight: 400; line-height: 120%;"
                  href="$urlredirect">
                    Voir l\'événement sur la plateforme
                  </a>
              </td></tr></table></a>
            </td>
          </tr>
          <tr>	
            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
              padding-top: 25px;" class="line"><hr
              color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
            </td>
          </tr>
          <tr>
            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%;" class="list-item"><table align="center" border="0" cellspacing="0" cellpadding="0" style="width: inherit; margin: 0; padding: 0; border-collapse: collapse; border-spacing: 0;">
                    <tr>
        
                        <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0;
                            padding-top: 30px;
                            padding-right: 20px;"><img
                        border="0" vspace="0" hspace="0" style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;
                            color: #000000;"
                            src="https://croix-rouge-ixelles.com/libs/img/maison.png"
                            width="50" height="50"></td>
        
                        <td align="left" valign="top" style="font-size: 17px; font-weight: 400; line-height: 160%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;
                            padding-top: 25px;
                            color: #000000;
                            font-family: sans-serif;" class="paragraph">
                                <b style="color: #333333;">Adresse de l\'événement</b><br/>
                                $adresse
                        </td>
        
                    </tr>
                    <tr>
                <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0;
                  padding-top: 30px;
                  padding-right: 20px;"><img
                border="0" vspace="0" hspace="0" style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;
                  color: #000000;"
                  src="https://croix-rouge-ixelles.com/libs/img/horloge.png"
                  width="50" height="50"></td>
                <td align="left" valign="top" style="font-size: 17px; font-weight: 400; line-height: 160%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;
                  padding-top: 25px;
                  color: #000000;
                  font-family: sans-serif;" class="paragraph">
                    <b style="color: #333333;">Heure de l\'événement</b><br/>
                    De $start à $end
                </td>
        
              </tr>
        
              <tr>
        
                <td align="left" valign="top" style="border-collapse: collapse; border-spacing: 0;
                  padding-top: 30px;
                  padding-right: 20px;"><img
                border="0" vspace="0" hspace="0" style="padding: 0; margin: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; border: none; display: block;
                  color: #000000;"
                  src="https://croix-rouge-ixelles.com/libs/img/fiche.png"
                  width="50" height="50"></td>
        
                <td align="left" valign="top" style="font-size: 17px; font-weight: 400; line-height: 160%; border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0;
                  padding-top: 25px;
                  color: #000000;
                  font-family: sans-serif;" class="paragraph">
                    <b style="color: #333333;">Description et info</b><br/>
                    $description
                </td>
        
              </tr>
        
        
            </table></td>
          </tr>
        
          <tr>
            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%;
              padding-top: 25px;" class="line"><hr
              color="#E0E0E0" align="center" width="100%" size="1" noshade style="margin: 0; padding: 0;" />
            </td>
          </tr>
        
          <tr>
            <td align="center" valign="top" style="border-collapse: collapse; border-spacing: 0; margin: 0; padding: 0; padding-left: 6.25%; padding-right: 6.25%; width: 87.5%; font-size: 17px; font-weight: 400; line-height: 160%;
              padding-top: 20px;
              padding-bottom: 25px;
              color: #000000;
              font-family: sans-serif;" class="paragraph">
                Merci d\'indiquer vos disponibilités
            </td>
          </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" align="center"
          width="560" style="border-collapse: collapse; border-spacing: 0; padding: 0; width: inherit;
          max-width: 560px;" class="wrapper">
        
        </table>
        
        </td></tr></table>
        
        </body>
        </html>';

        $body2 = str_replace('$titre', $titre, $body);
        $body3 = str_replace('$date', $date, $body2);
        $body4 = str_replace('$adresse', $adresse, $body3);
        $body5 = str_replace('$start', $start, $body4);
        $body6 = str_replace('$end', $end, $body5);
        $body7 = str_replace('$description', $description, $body6);
        $body8 = str_replace('$urlredirect', $urlredirect, $body7);

        try {

          $mail->SMTPDebug = 0;
          $mail->isSMTP();
          $mail->Host       = 'ssl://smtp.gmail.com';
          $mail->SMTPAuth   = true;
          $mail->Username   = 'CHANGER-MOI'; // adresse email de l'expéditeur
          $mail->Password   = 'CHANGER-MOI'; // mots de passe de l'adresse email
          $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_SMTPS';
          $mail->Port       = 465;
          $mail->CharSet = 'UTF-8';


          $mail->setFrom('CHANGER-MOI', 'Ixelles Croix-Rouge'); // adresse email de l'expéditeur
          foreach ($users as $user) {
            $mail->addAddress($user['email'], $user['name']);
          };
          $mail->isHTML(true);
          $mail->Subject = "Préventif ajouté - $titre";
          $mail->Body    =  $body8;
          $mail->AltBody = 'Charger le mail HTML';

          $mail->send();
        } catch (Exception $e) {
          $session->msg("s", "Echec de l'envoie du mail. Erreur : {$mail->ErrorInfo}.");
        }
      }

      if ($_POST['sms'] == "ok") {
        $endpoint = 'ovh-eu';
        $applicationKey = "CHANGER-MOI";
        $applicationSecret = "CHANGER-MOI";
        $consumer_key = "CHANGER-MOI";

        $conn2 = new Api(
          $applicationKey,
          $applicationSecret,
          $endpoint,
          $consumer_key
        );

        $smsServices = $conn2->get('/sms');
        foreach ($smsServices as $smsService) {

          print_r($smsService);
        }
        $body = 'Préventif ajouté : $titre le $date de $p_timeeventstart à $p_timeeventend ADRESSE : $p_adresse';

        $datebonformat = date("d-m-Y", strtotime($p_dateevent));

        $body2 = str_replace('$titre', $_POST['titleevent'], $body);
        $body3 = str_replace('$date', $datebonformat, $body2);
        $body4 = str_replace('$p_timeeventstart', $p_timeeventstart, $body3);
        $body5 = str_replace('$p_timeeventend', $p_timeeventend, $body4);
        $body6 = str_replace('$p_adresse', $p_adresse, $body5);
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
      }

      if ($_POST['notif'] == 'ok') {
        $datebonformat = date("d-m-Y", strtotime($p_dateevent));
        $msg = array(
          'body'  => $_POST['titleevent'] . " le " . $datebonformat,
          'title'     => "Préventif ajouté",
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
      }


      $session->msg('s', "Evénement ajouté");
      redirect("index", false);
    } else {
      $session->msg('d', ' Désolé, échec de l\'ajout!');
      redirect("add_event?date=$dateevent", false);
    }
  } else {
    $session->msg("d", $errors);
    redirect("add_event?date=$dateevent", false);
  }
}
$orgDate = $dateevent;
$newDate = date("d-m-Y", strtotime($orgDate));
?>

<?php include_once('layouts/header.php');
echo display_msg($msg); ?>
<section class="py-5">
  <div class="container px-2">
    <!-- Contact form-->
    <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
      <div class="text-center mb-5">
        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-calendar3-range"></i></div>
        <h1 class="fw-bolder">Ajouter un préventif</h1>
        <p class="lead fw-normal text-muted mb-0"><?php echo $newDate ?></p>
      </div>
      <div class="row gx-5 justify-content-center">
        <div class="col-lg-8 col-xl-6">
          <form method="post" action="add_event.php?date=<?php echo $dateevent ?>" class="clearfix">
            <div class="form-floating mb-3">
              <input class="form-control" name="titleevent" placeholder="Nom de l'événement" type="text" required />
              <label for="name">Nom du prev</label>
            </div>
            <div class="input-group mb-3">
              <input type="time" class="form-control" name="timeeventstart">
              <input type="time" class="form-control" name="timeeventend">
            </div>
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="adresse" placeholder="Adresse de l'événement">
              <label for="adres">Adresse du prev</label>
            </div>
            <div class="form-floating mb-3">
              <input type="number" class="form-control" name="personnel" placeholder="Nombre de volontaires">
              <label for="vol">Nombre de volontaires</label>
            </div>
            <div class="form-floating mb-3">
              <textarea class="form-control" name="description" placeholder="Description" type="text" style="height: 10rem"></textarea>
              <label for="message">Description</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="ok" id="flexCheckChecked" name="sms">
              <label class="form-check-label" for="flexCheckChecked">
                Envoi SMS <?php echo $pricesms ?> € (URGENT)
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="ok" id="flexCheckChecked" name="email" checked>
              <label class="form-check-label" for="flexCheckChecked">
                Envoi E-MAIL
              </label>
            </div>
            <?php foreach ($evenements as $evenement) {
              if ($evenement['date'] == $dateevent) {
                $view[] = true;
              }
            }
            if (!empty($view) && in_array(true, $view)) : ?>
              <div class="card shadow border-0">
                <div class="card-body">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="oui" id="flexCheckChecked" name="doubleprev">
                    <label class="form-check-label" for="flexCheckChecked">
                      Autoriser le double préventif ?
                    </label>
                  </div>
                </div>
              </div>
            <?php endif; ?>
            <br>
            <div class="d-grid"><button class="btn btn-primary btn-lg" name="add_event" type="submit">Ajouter</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once('layouts/footer.php'); ?>