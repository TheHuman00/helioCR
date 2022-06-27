<?php
$page_title = 'Utilisateurs';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}
page_require_level(1);
$user = current_user();
$all_users = find_all_user();
$users = join_user_table2();
$all_dispos = join_dispo_table();
$calendriers = join_calen_table();

date_default_timezone_set('Europe/Brussels');
$dateactu = date("m");
$dateyear = date("Y");
$now = date('Y-m-d');
if ($dateactu == "01" || $dateactu == "02" || $dateactu == "03") {
  $datetrim = array("01", "02", "03");
}
if ($dateactu == "04" || $dateactu == "05" || $dateactu == "06") {
  $datetrim = array("04", "05", "06");
}
if ($dateactu == "07" || $dateactu == "08" || $dateactu == "09") {
  $datetrim = array("07", "08", "09");
}
if ($dateactu == "10" || $dateactu == "11" || $dateactu == "12") {
  $datetrim = array("10", "11", "12");
}

$counteventuntilnow = 0;
foreach ($calendriers as $calendrier) {
  $datemois = explode("-", $calendrier['date']);
  if ($calendrier['date'] <= $now) {
    if ($datemois[1] == $datetrim[0] || $datemois[1] == $datetrim[1] || $datemois[1] == $datetrim[2]) {
      $counteventuntilnow = $counteventuntilnow + 1;
    }
  }
}







require __DIR__ . '/vendor/autoload.php';

use \Ovh\Api;

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
$resultcostsms = $conn->get('/sms/rates/destinations', array(
  'billingCountry' => 'fr',
  'country' => 'be',
));
$resultcredits = $conn->get('/sms/sms-bg693826-1');
$costsms = $resultcostsms['credit'];
$credits = $resultcredits['creditsLeft'];
$count2 = 0;
foreach ($users as $userrr) {
  $count2 = $count2 + 1;
}
$smsleft2 = $credits / $costsms;
$smsleft = floor($smsleft2);
$vagueleft2 = $smsleft2 / $count2;
$vagueleft = floor($vagueleft2);
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
  <div class="container px-2">
    <h1 class="fw-bolder fs-5 mb-4">Utilisateurs : </h1>
    <div class="card border-0 shadow rounded-3 overflow-hidden">
      <div class="card-body p-0">
        <div class="row gx-0">
          <div class="p-2 p-md-2">
            <div class="autre">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th class="col">#</th>
                    <th class="col">Utilisateurs</th>
                    <th class="col">Formation</th>
                    <th class="col">Email</th>
                    <th class="col">Téléphone</th>
                    <th class="col">Préventifs 3 mois</th>
                    <th class="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($all_users as $a_user) : ?>

                    <tr>
                      <td><?php echo count_id(); ?></td>
                      <td><?php echo remove_junk(ucwords($a_user['username']));
                          if ($a_user['admin'] == true) {
                            echo "💼";
                          };
                          if ($a_user['permis'] == true) {
                            echo "🚗";
                          };
                          if ($a_user['cle'] == true) {
                            echo "🔑";
                          }; ?></td>
                      <td><?php if (str_contains($a_user['competence'], "beps")) {
                            echo "BEPS";
                          };
                          if (str_contains($a_user['competence'], "secouriste")) {
                            echo "Secouriste";
                          };
                          if (str_contains($a_user['competence'], "105")) {
                            echo "Ambu 105";
                          };
                          if (str_contains($a_user['competence'], "amu2")) {
                            echo "AMU";
                          };
                          if (str_contains($a_user['competence'], "infi2")) {
                            echo "Infi";
                          };
                          if (str_contains($a_user['competence'], "infisiamu")) {
                            echo "Infi SIAMU";
                          };
                          if (str_contains($a_user['competence'], "medecin")) {
                            echo "Médecin";
                          }; ?></td>
                      <td><?php echo $a_user['email']?></td>
                      <td><?php echo $a_user['telephone']?></td>
                      <td><?php if ($a_user['name'] == "Deville Michael") {
                            echo "X";
                          } else {
                            $count = 0;
                            foreach ($all_dispos as $dispo) {
                              if ($a_user['username'] == $dispo['user']) {
                                $datemois = explode("-", $dispo['date']);
                                if ($dateyear == $datemois[0]) {
                                  if ($datemois[1] == $datetrim[0] || $datemois[1] == $datetrim[1] || $datemois[1] == $datetrim[2]) {
                                    if ($dispo['date'] <= $now) {
                                      if ($dispo['dispo'] == "2") {
                                        $count = $count + 1;
                                      }
                                    }
                                  }
                                }
                              }
                            }
                            if ($counteventuntilnow == 0) {
                              echo "N/A";
                            } else {
                              $countdecimal = $count / $counteventuntilnow;
                              $countpourcentage = $countdecimal * 100;
                              echo round($countpourcentage) . "%";
                            }
                          }
                          ?></td>
                      <td>
                        <div class="btn-group">
                          <a href="view_user?id=<?php echo (int)$a_user['id']; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Voir">
                            <i class="bi bi-eye"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

            </div>

          </div>
          <div class="phone">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="col">Users</th>
                  <th class="col">Formation</th>
                  <th class="col">Prév. 3 mois</th>
                  <th class="col"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($all_users as $a_user) : ?>

                  <tr>
                    <td><?php echo remove_junk(ucwords($a_user['username']));
                        if ($a_user['admin'] == true) {
                          echo "💼";
                        };
                        if ($a_user['permis'] == true) {
                          echo "🚗";
                        };
                        if ($a_user['cle'] == true) {
                          echo "🔑";
                        }; ?></td>
                    <td><?php if (str_contains($a_user['competence'], "beps")) {
                          echo "BEPS";
                        };
                        if (str_contains($a_user['competence'], "secouriste")) {
                          echo "Secouriste";
                        };
                        if (str_contains($a_user['competence'], "105")) {
                          echo "Ambu 105";
                        };
                        if (str_contains($a_user['competence'], "amu2")) {
                          echo "AMU";
                        };
                        if (str_contains($a_user['competence'], "infi2")) {
                          echo "Infi";
                        };
                        if (str_contains($a_user['competence'], "infisiamu")) {
                          echo "Infi SIAMU";
                        };
                        if (str_contains($a_user['competence'], "medecin")) {
                          echo "Médecin";
                        }; ?></td>
                    <td><?php if ($a_user['name'] == "Deville Michael") {
                          echo "X";
                        } else {
                          $count = 0;
                          foreach ($all_dispos as $dispo) {
                            if ($a_user['username'] == $dispo['user']) {
                              $datemois = explode("-", $dispo['date']);
                              if ($dateyear == $datemois[0]) {
                                if ($datemois[1] == $datetrim[0] || $datemois[1] == $datetrim[1] || $datemois[1] == $datetrim[2]) {
                                  if ($dispo['date'] < $now) {
                                    if ($dispo['dispo'] == "2") {
                                      $count = $count + 1;
                                    }
                                  }
                                }
                              }
                            }
                          }
                          if ($counteventuntilnow == 0) {
                            echo "N/A";
                          } else {
                            $countdecimal = $count / $counteventuntilnow;
                            $countpourcentage = $countdecimal * 100;
                            echo round($countpourcentage) . "%";
                          }
                        }
                        ?></td>
                    <td>
                      <div class="btn-group">
                        <a href="view_user?id=<?php echo (int)$a_user['id']; ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Voir">
                          <i class="bi bi-eye"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <br>
  </div>
  <div class="container px-3">
    <h2 class="fw-bolder fs-5 mb-4">Légendes :</h2>
    <p class="text-muted mb-0">Nombres de préventifs passés sur 3 mois --> <strong><?php echo $counteventuntilnow ?></strong></p>
    <p class="text-muted mb-0">Fin de cycle --> <strong><?php echo $datetrim[2];
                                                        echo "-" . $dateyear ?></strong></p>
    <p class="text-muted mb-0"><strong><?php echo $smsleft ?></strong> SMS restant --> <strong><?php echo $vagueleft; ?></strong> vagues possible</p>
    <div class="row gx-5 row-cols-2 row-cols-lg-4 py-5">
      <div class="col">
        <div class="feature bg-light bg-gradient text-white rounded-3 mb-3"><i>💼</i></div>
        <p class="text-muted mb-0">Membre du comité</p>
      </div>
      <div class="col">
        <div class="feature bg-light bg-gradient text-white rounded-3 mb-3"><i>🚗</i></div>
        <p class="text-muted mb-0">Chauffeur Section-Locale</p>
      </div>
      <div class="col">
        <div class="feature bg-light bg-gradient text-white rounded-3 mb-3"><i>🔑</i></div>
        <p class="text-muted mb-0">Détenteur des clés de la section</p>
      </div>
      <div class="col">
        <div class="feature bg-light bg-gradient text-black rounded-3 mb-3">N/A</div>
        <p class="text-muted mb-0">Pas de préventif depuis le début du trimestre</p>
      </div>
    </div>
  </div>
</section>
<?php include_once('layouts/footer.php'); ?>