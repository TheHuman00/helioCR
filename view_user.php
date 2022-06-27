<?php
$page_title = 'Profil volontaires';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
$user = current_user();
$id_user = find_by_id('users', (int)$_GET['id']);
$date = make_date();
$moiactu = date("m");


?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5 bg-light">
  <div class="container px-5">
    <div class="row gx-5">
      <div class="col-xl-8">
        <a style="color:#6c757d" href="<?php if($user['user_level'] == 1) { echo "./utilisateuradmin";}else{ echo "./utilisateur";}?>">
          <i class="bi bi-arrow-left"></i>
          Retour page volontaire
        </a>
        <h1 class="fw-bolder"><?php echo $id_user['name'] ?></h1>
        <br>
        <div class="card shadow border-0">
          <div class="card-body">
            <h5 class="card-title mb-3 text-center">Adresse email :</h5>
            <p class="card-text mb-0 text-center"><?php echo $id_user['email'] ?></p>
          </div>
        </div>
        <br>
        <div class="card shadow border-0">
          <div class="card-body">
            <h5 class="card-title mb-3 text-center">Numéro de téléphone :</h5>
            </a>
            <p class="card-text mb-0 text-center"><?php echo $id_user['telephone'] ?></p>
          </div>
        </div>
        <br>


      </div>
      <div class="col-xl-4">
        <div class="card border-0 h-100">
          <div class="card-body p-4">
            <div class="d-flex h-100 align-items-center justify-content-center">
              <div class="text-center">
                <div class="h6 fw-bolder">Formation</div>
                <p><?php if (str_contains($id_user['competence'], "beps")) {
                      echo "BEPS";
                    };
                    if (str_contains($id_user['competence'], "secouriste")) {
                      echo "Secouriste";
                    };
                    if (str_contains($id_user['competence'], "105")) {
                      echo "Ambu 105";
                    };
                    if (str_contains($id_user['competence'], "amu2")) {
                      echo "AMU";
                    };
                    if (str_contains($id_user['competence'], "infi2")) {
                      echo "Infi";
                    };
                    if (str_contains($id_user['competence'], "infisiamu")) {
                      echo "Infi SIAMU";
                    };
                    if (str_contains($id_user['competence'], "medecin")) {
                      echo "Médecin";
                    }; ?></p>
                <hr>
                <div class="h6 fw-bolder">Compétence</div>
                <p><?php
                    if ($id_user['admin'] == true) {
                      echo "💼 ";
                    };
                    if ($id_user['permis'] == true) {
                      echo "🚗 ";
                    };
                    if ($id_user['cle'] == true) {
                      echo "🔑 ";
                    };
                    if (empty($id_user['admin']) && empty($id_user['permis']) && empty($id_user['cle'])) {
                      echo "---";
                    }; ?></p>
                    <?php if ($user['user_level'] == 1 || $user['user_level'] == 2) :?>
                <a href="edit_user?id=<?php echo (int)$_GET['id']; ?>" class="btn btn-m btn-warning" data-toggle="tooltip" title="Editer">
                  <i class="bi bi-pencil"></i>
                </a>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php if ($user['user_level'] == 1 || $user['user_level'] == 2) :?>
<?php
function calendrier($annee, $trim1, $trim2, $trim3)
{
  $calendriers = join_calen_table();
  $countevent = 0;
  foreach ($calendriers as $calendrier) {
    $datexplode = explode("-", $calendrier['date']);
    if ($datexplode[0] == $annee) {
      if ($datexplode[1] == $trim1 || $datexplode[1] == $trim2 || $datexplode[1] == $trim3) {
        $countevent = $countevent + 1;
      }
    }
  }
  return $countevent;
};

function dispo($annee, $trim1, $trim2, $trim3)
{
  $all_dispos = join_dispo_table();
  $id_user = find_by_id('users', (int)$_GET['id']);
  $countdispo = 0;
  foreach ($all_dispos as $dispo) {
    if ($id_user['name'] == $dispo['user']) {
      $datexplode = explode("-", $dispo['date']);
      if ($datexplode[0] == $annee) {
        if ($datexplode[1] == $trim1 || $datexplode[1] == $trim2 || $datexplode[1] == $trim3) {
          if ($dispo['dispo'] == "2") {
            $countdispo = $countdispo + 1;
          }
        }
      }
    }
  }
  return $countdispo;
}
?>
<section class="py-5">
  <div class="container px-2">
    <h1 class="fw-bolder fs-5 mb-4">Historique de pourcentage :</h1>
    <div class="card border-0 shadow rounded-3 overflow-hidden">
      <div class="card-body p-0">
        <div class="row gx-0">
          <div class="p-2 p-md-2">
            <div class="h5 text-center">2021</div>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="col">Période</th>
                  <th class="col">Prev. / Total</th>
                  <th class="col">Pourcentage</th>
                </tr>
              </thead>
              <tbody>

                <?php if (calendrier(2021, 10, 11, 12) !== 0) : ?>
                  <tr>
                    <td><?php echo "10->12" ?></td>
                    <td><?php echo dispo(2021, 10, 11, 12) . " / " . calendrier(2021, 10, 11, 12); ?></td>

                    <?php if (calendrier(2021, 10, 11, 12) == 0) {
                      $text42021 = "N/A";
                    } else {
                      $decimal42021 = dispo(2021, 10, 11, 12) / calendrier(2021, 10, 11, 12);
                      $pourcentage42021 = $decimal42021 * 100;
                      $text42021 = round($pourcentage42021) . "%";
                    }
                    ?>

                    <td><?php echo $text42021; ?></td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="card border-0 shadow rounded-3 overflow-hidden">
      <div class="card-body p-0">
        <div class="row gx-0">
          <div class="p-2 p-md-2">
            <div class="h5 text-center">2022</div>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="col">Période</th>
                  <th class="col">Prev. / Total</th>
                  <th class="col">Pourcentage</th>
                </tr>
              </thead>
              <tbody>
                <?php if (calendrier(2022, 01, 02, 03) !== 0) : ?>
                  <tr>
                    <td><?php echo "01->03" ?></td>
                    <td><?php echo dispo(2022, 01, 02, 03) . " / " . calendrier(2022, 01, 02, 03); ?></td>

                    <?php if (calendrier(2022, 01, 02, 03) == 0) {
                      $text12022 = "N/A";
                    } else {
                      $decimal12022 = dispo(2022, 01, 02, 03) / calendrier(2022, 01, 02, 03);
                      $pourcentage12022 = $decimal12022 * 100;
                      $text12022 = round($pourcentage12022) . "%";
                    }
                    ?>

                    <td><?php echo $text12022; ?></td>
                  </tr>
                <?php endif;
                if (calendrier(2022, 04, 05, 06) !== 0) : ?>
                  <tr <?php if ($moiactu == "04" || $moiactu == "05" || $moiactu == "06") {
                        echo "style=\"background: #ced4da;\"";
                      } ?>>
                    <td><?php echo "04->06" ?></td>
                    <td><?php echo dispo(2022, 04, 05, 06) . " / " . calendrier(2022, 04, 05, 06); ?></td>

                    <?php if (calendrier(2022, 04, 05, 06) == 0) {
                      $text22022 = "N/A";
                    } else {
                      $decimal22022 = dispo(2022, 04, 05, 06) / calendrier(2022, 04, 05, 06);
                      $pourcentage22022 = $decimal22022 * 100;
                      $text22022 = round($pourcentage22022) . "%";
                    }
                    ?>

                    <td><?php echo $text22022; ?></td>
                  </tr>
                <?php endif;
                if (calendrier(2022, 7, 8, 9) !== 0) : ?>
                  <tr <?php if ($moiactu == "07" || $moiactu == "08" || $moiactu == "09") {
                        echo "style=\"background: #ced4da;\"";
                      } ?>>
                    <td><?php echo "07->09" ?></td>
                    <td><?php echo dispo(2022, 7, 8, 9) . " / " . calendrier(2022, 7, 8, 9); ?></td>

                    <?php if (calendrier(2022, 7, 8, 9) == 0) {
                      $text32022 = "N/A";
                    } else {
                      $decimal32022 = dispo(2022, 7, 8, 9) / calendrier(2022, 7, 8, 9);
                      $pourcentage32022 = $decimal32022 * 100;
                      $text32022 = round($pourcentage32022) . "%";
                    }
                    ?>

                    <td><?php echo $text32022; ?></td>
                  </tr>
                <?php endif;
                if (calendrier(2022, 10, 11, 12) !== 0) : ?>
                  <tr>
                    <td><?php echo "10->12" ?></td>

                    <td><?php echo dispo(2022, 10, 11, 12) . " / " . calendrier(2022, 10, 11, 12); ?></td>

                    <?php if (calendrier(2022, 10, 11, 12) == 0) {
                      $text42022 = "N/A";
                    } else {
                      $decimal42022 = dispo(2022, 10, 11, 12) / calendrier(2022, 10, 11, 12);
                      $pourcentage42022 = $decimal42022 * 100;
                      $text42022 = round($pourcentage42022) . "%";
                    }
                    ?>

                    <td><?php echo $text42022; ?></td>
                  </tr>
                <?php endif; ?>
              <tbody>
            </table>
          </div>
        </div>
      </div>
    </div>



</section>
<?php endif; ?>
<?php include_once('layouts/footer.php'); ?>