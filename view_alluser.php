<?php
$page_title = 'Profil volontaires';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
$user = current_user();
$all_users = find_all_user();
$date = make_date();
$moiactu = date("m");


?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg);
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

function dispo($annee, $trim1, $trim2, $trim3, $id_user)
{
  $all_dispos = join_dispo_table();
  $user = find_by_id('users', $id_user);
  $countdispo = 0;
  foreach ($all_dispos as $dispo) {
    if ($user['name'] == $dispo['user']) {
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
    <a style="color:#6c757d" href="<?php if ($user['user_level'] == 1) {
                                      echo "./utilisateuradmin";
                                    } else {
                                      echo "./utilisateur";
                                    } ?>">
      <i class="bi bi-arrow-left"></i>
      Retour page volontaire
    </a>
    <h1 class="fw-bolder fs-5 mb-4">Historique de pourcentage :</h1>
    <div class="card border-0 shadow rounded-3 overflow-hidden">
      <div class="card-body p-0">
        <div class="row gx-0">
          <div class="p-2 p-md-2">
            <table class="table table-bordered table-striped" style="overflow: auto;">
              <thead>
                <tr>
                  <th class="col">Volontaire</th>
                  <?php if (calendrier(2021, 10, 11, 12) !== 0) : ?>
                    <th class="col">2021 T4</th>
                  <?php endif; ?>
                  <?php if (calendrier(2022, 01, 02, 03) !== 0) : ?>
                    <th class="col">2022 T1</th>
                  <?php endif; ?>
                  <?php if (calendrier(2022, 04, 05, 06) !== 0) : ?>
                    <th class="col">2022 T2</th>
                  <?php endif; ?>
                  <?php if (calendrier(2022, 7, 8, 9) !== 0) : ?>
                    <th class="col">2022 T3</th>
                  <?php endif; ?>
                  <?php if (calendrier(2022, 10, 11, 12) !== 0) : ?>
                    <th class="col">2022 T4</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($all_users as $users) : ?>
                  <tr>
                    <td><?php echo $users['name']; ?></td>
                    <?php if (calendrier(2021, 10, 11, 12) !== 0) : ?>
                      <?php if (calendrier(2021, 10, 11, 12) == 0) {
                        $text42021 = "N/A";
                      } else {
                        $decimal42021 = dispo(2021, 10, 11, 12, $users['id']) / calendrier(2021, 10, 11, 12);
                        $pourcentage42021 = $decimal42021 * 100;
                        $text42021 = round($pourcentage42021) . "%";
                      }
                      ?>

                      <td><?php echo $text42021; ?></td>
                    <?php endif; ?>

                    <?php if (calendrier(2022, 01, 02, 03) !== 0) : ?>
                      <?php if (calendrier(2022, 01, 02, 03) == 0) {
                        $text12022 = "N/A";
                      } else {
                        $decimal12022 = dispo(2022, 01, 02, 03, $users['id']) / calendrier(2022, 01, 02, 03);
                        $pourcentage12022 = $decimal12022 * 100;
                        $text12022 = round($pourcentage12022) . "%";
                      }
                      ?>

                      <td><?php echo $text12022; ?></td>
                    <?php endif; ?>

                    <?php if (calendrier(2022, 04, 05, 06) !== 0) : ?>
                      <?php if (calendrier(2022, 04, 05, 06) == 0) {
                        $text22022 = "N/A";
                      } else {
                        $decimal22022 = dispo(2022, 04, 05, 06, $users['id']) / calendrier(2022, 04, 05, 06);
                        $pourcentage22022 = $decimal22022 * 100;
                        $text22022 = round($pourcentage22022) . "%";
                      }
                      ?>

                      <td><?php echo $text22022; ?></td>
                    <?php endif; ?>

                    <?php if (calendrier(2022, 7, 8, 9) !== 0) : ?>
                      <?php if (calendrier(2022, 7, 8, 9) == 0) {
                        $text32022 = "N/A";
                      } else {
                        $decimal32022 = dispo(2022, 7, 8, 9, $users['id']) / calendrier(2022, 7, 8, 9);
                        $pourcentage32022 = $decimal32022 * 100;
                        $text32022 = round($pourcentage32022) . "%";
                      }
                      ?>

                      <td><?php echo $text32022; ?></td>
                    <?php endif; ?>

                    <?php if (calendrier(2022, 10, 11, 12) !== 0) : ?>
                      <?php if (calendrier(2022, 10, 11, 12) == 0) {
                        $text42022 = "N/A";
                      } else {
                        $decimal42022 = dispo(2022, 10, 11, 12, $users['id']) / calendrier(2022, 10, 11, 12);
                        $pourcentage42022 = $decimal42022 * 100;
                        $text42022 = round($pourcentage42022) . "%";
                      }
                      ?>

                      <td><?php echo $text42022; ?></td>
                    <?php endif; ?>
                  </tr>
                <?php endforeach; ?>
              <tfoot>
                <tr>
                  <td><strong>Total de prev.</strong></td>
                  <td><strong><?php echo calendrier(2021, 10, 11, 12); ?></strong></td>
                  <td><strong><?php echo calendrier(2022, 01, 02, 03); ?></strong></td>
                  <td><strong><?php echo calendrier(2022, 04, 05, 06); ?></strong></td>
                  <?php if (calendrier(2022, 7, 8, 9) !== 0) : ?>
                    <td><strong><?php echo calendrier(2022, 7, 8, 9); ?></strong></td>
                  <?php endif; ?>
                  <?php if (calendrier(2022, 10, 11, 12) !== 0) : ?>
                    <td><strong><?php echo calendrier(2022, 10, 11, 12); ?></strong></td>
                  <?php endif; ?>
                </tr>
              </tfoot>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>



</section>
<?php include_once('layouts/footer.php'); ?>