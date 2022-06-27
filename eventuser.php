<?php
$page_title = 'Préventif';
require_once('./includes/load.php');
$user = current_user();
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
?>
<?php
$nameevent = $_GET['nom'];
$disponibilites = join_dispo_table();
$calendrier = find_event_by_title($nameevent);
$dispos = find_disponibilite_by_title($nameevent, $user['name']);
$evenements = join_calen_table();
?>
<?php
$date2 = $nameevent;
$date = explode("DATE", $date2);
$datecal = $date[1];
if (isset($_POST['disponi'])) {
  if (empty($errors)) {
    $p_dispo   = remove_junk($db->escape($_POST['dispo']));
    $p_comm = $_POST['comm'];
    $query2  = "INSERT INTO disponibilite (";
    $query2 .= "user,event,date,dispo,comm,rolecp,rolechau";
    $query2 .= ") VALUES (";
    $query2 .= "'{$user['name']}', '{$nameevent}', '{$date['1']}', '{$p_dispo}', '{$p_comm}', '', ''";
    $query2 .= ") ON DUPLICATE KEY UPDATE user ='{$user['name']}', event ='{$nameevent}', date='{$date[1]}', dispo ='{$p_dispo}', comm ='{$p_comm}', rolecp='non', rolechau='non'";
    $result2 = $db->query($query2);
    if ($result2) {
      if($p_dispo !== "0"){
        foreach ($disponibilites as $disponibilite) {
          if ($disponibilite['user'] == $user['name']) {
            if (str_contains($disponibilite['event'], $date[1])) {
              if ($disponibilite['event'] == $nameevent) {
                $session->msg("s", "Disponibilité donné avec succes.");
              } else {
                if ($disponibilite['dispo'] == "0") {
                  $session->msg("s", "Disponibilité donné avec succes.");
                } else {
                  if ($calendrier['doubleprev'] == "non" && $disponibilite['dispo'] !== "0") {
                    $sql = "DELETE FROM disponibilite";
                    $sql .= " WHERE id=" . $disponibilite['id'];
                    $sql .= " LIMIT 1";
                    $result = $db->query($sql);
                    if ($result) {
                      $session->msg('s', "Double prev impossible suppression de participation à un");
                    }
                  } else {
                    $session->msg("s", "Disponibilité donné avec succes.");
                  }
                }
              }
            } else {
              $session->msg("s", "Disponibilité donné avec succes.");
            }
          } else {
            $session->msg("s", "Disponibilité donné avec succes.");
          }
        }
      } else {
        $session->msg("s", "Disponibilité donné avec succes.");
      }
      if ($count == ($calendrier['personnel'] - 1)) {
        historique($nameevent, $user['name'], $p_dispo);
        redirect("emaildispo?url=$nameevent");
      } else {
        historique($nameevent, $user['name'], $p_dispo);
        redirect("eventuser?nom=$nameevent");
      }
    } else {
      $session->msg('s', "Erreur sur la demande de participation ");
      redirect("eventuser?nom=$nameevent");
    }
  } else {
    $session->msg('s', "Erreur sur la demande de participation ");
    redirect("eventuser?nom=$nameevent");
  }
}
$count = 0;
foreach ($disponibilites as $disponibilite) {
  if ($disponibilite['event'] == $nameevent) {
    if ($disponibilite['dispo'] == "1" || $disponibilite['dispo'] == "2") {
      if (!str_contains($disponibilite['comm'], "apd") && !str_contains($disponibilite['comm'], "Apd") && !str_contains($disponibilite['comm'], "APD")) {
        $count = $count + 1;
      }
    }
  }
}

$c_count = $count;

$orgDate = $calendrier['date'];
$newDate = date("d-m-Y", strtotime($orgDate));
?>


<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
  <div class="container px-4">
    <h1 class="fw-bolder fs-5 mb-4">Préventif :</h1>
    <div class="card border-0 shadow rounded-3 overflow-hidden">
      <div class="card-body p-0">
        <div class="row gx-0">
          <div class="col-lg-6 col-xl-5 py-lg-5">
            <div class="p-4 p-md-5">
              <div class="badge bg-primary bg-gradient rounded-pill mb-2"><?php echo $calendrier['start'] ?>-<?php echo $calendrier['end'] ?></div>
              <div class="h2 fw-bolder"><?php $titre2 = $nameevent;
                                        $titre = explode("DATE", $titre2);
                                        echo $titre[0] ?> <br> <?php echo $newDate ?></div>
              <div><?php echo nl2br($calendrier['description']) ?></div>
              <?php foreach ($evenements as $evenement) {
                if ($evenement['date'] == $calendrier['date']) {
                  if ($evenement['title'] !== $calendrier['title']) {
                    if ($calendrier['doubleprev'] == "oui") {
                      echo "<p class=\"text-muted\">Double préventif accepté</p>";
                    }
                    if ($calendrier['doubleprev'] == "non") {
                      echo "<p class=\"text-muted\">Double préventif refusé</p>";
                    }
                  }
                }
              } ?>
              <p>Effectif nécessaire : <strong><?php echo $c_count ?>/<?php echo $calendrier['personnel'] ?></strong></p>
              <a style="color:#0d6efd" href="https://maps.google.com/maps?q=<?php echo $calendrier['adresse'] ?>">
                <?php echo $calendrier['adresse'] ?>
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
          <div class="col-lg-6 col-xl-7">
            <div class="mapouter">
              <div class="gmap_canvas">
                <iframe width="100%" height="100%" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo $calendrier['adresse'] ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" marginheight="0" marginwidth="0" allow="fullscreen"></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
<section class="py-5 bg-light">
  <div class="container px-4">
    <div class="row gx-5">
      <div class="col-xl-8">
        <h2 class="fw-bolder fs-5 mb-4">Effectifs :</h2>
        <h5>Personnes présentes (acceptées): </h5>
        <?php foreach ($disponibilites as $disponibilite) : ?>
          <?php if ($disponibilite['event'] == $nameevent) : ?>
            <?php if ($disponibilite['dispo'] == "2") : ?>
              <ul style="color:green"> - <?php echo $disponibilite['user']; ?> <?php if ($disponibilite['comm'] !== "") {
                                                                                  echo "(" . $disponibilite['comm'] . ")";
                                                                                } ?> <?php if ($disponibilite['rolecp'] == "oui") {
                                                                                        echo "👑";
                                                                                      } ?><?php if ($disponibilite['rolechau'] == "oui") {
                                                                                            echo "🚗";
                                                                                          } ?></ul>
            <?php endif; ?>
          <?php endif; ?>
        <?php endforeach; ?>
        <hr>
        <div class="mb-4">
          <h5>Personnes disponibles (en attente):</h5>
          <?php foreach ($disponibilites as $disponibilite) : ?>
            <?php if ($disponibilite['event'] == $nameevent) : ?>
              <?php if ($disponibilite['dispo'] == "1") : ?>
                <ul style="color:orange"> - <?php echo $disponibilite['user'] ?> <?php if ($disponibilite['comm'] !== "") {
                                                                                    echo "(" . $disponibilite['comm'] . ")";
                                                                                  } ?> <?php if ($disponibilite['rolecp'] == "oui") {
                                                                                          echo "👑";
                                                                                        } ?><?php if ($disponibilite['rolechau'] == "oui") {
                                                                                              echo "🚗";
                                                                                            } ?></ul>
              <?php endif; ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <hr>
        <div class="mb-4">
          <h5>Personnes indisponibles ou refusées :</h5>
          <?php foreach ($disponibilites as $disponibilite) : ?>
            <?php if ($disponibilite['event'] == $nameevent) : ?>
              <?php if ($disponibilite['dispo'] == "0") : ?>
                <ul style="color:red"> - <?php echo $disponibilite['user'] ?> <?php if ($disponibilite['comm'] !== "") {
                                                                                echo "(" . $disponibilite['comm'] . ")";
                                                                              } ?> <?php if ($disponibilite['rolecp'] == "oui") {
                                                                                      echo "👑";
                                                                                    } ?><?php if ($disponibilite['rolechau'] == "oui") {
                                                                                          echo "🚗";
                                                                                        } ?></ul>
              <?php endif; ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <?php foreach ($disponibilites as $disponibilite) {
          if ($disponibilite['event'] !== $calendrier['title']) {
            if ($disponibilite['date'] == $calendrier['date']) {
              if ($disponibilite['dispo'] == "1" || $disponibilite['dispo'] == "2") {
                $view[] = true;
              }
            }
          }
        }
        if (!empty($view) && in_array(true, $view)) : ?>
          <div class="card shadow border-0">
            <div class="card-body">
              <h5 class="card-title mb-3">Sur autre préventif :</h5>
              <?php foreach ($disponibilites as $disponibilite) :
                if ($disponibilite['event'] !== $calendrier['title']) :
                  if ($disponibilite['date'] == $calendrier['date']) :
                    if ($disponibilite['dispo'] == "1" || $disponibilite['dispo'] == "2") : ?>
                      <li class="mb-0" <?php if ($disponibilite['dispo'] == "1") {
                                          echo "style=\"color:orange\"";
                                        };
                                        if ($disponibilite['dispo'] == "2") {
                                          echo "style=\"color:green\"";
                                        } ?>> <?php echo $disponibilite['user'] ?>
                      </li>
              <?php endif;
                  endif;
                endif;
              endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
        <br>
      </div>

      <div class="col-xl-4">
        <div class="card border-0 h-100">
          <div class="card-body p-4">
            <div class="d-flex h-100 align-items-center justify-content-center">
              <div class="text-center">
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-calendar-check"></i></div>
                <div class="h6 fw-bolder">Donner ses disponibilités</div>
                <form method="post" action="eventuser.php?nom=<?php echo $nameevent ?>" class="clearfix">
                  <div class="form-check" required>
                    <input class="form-check-input" type="radio" name="dispo" id="flexRadioDefault1" value="1" required>
                    <label class="form-check-label" for="flexRadioDefault1">
                      Disponible
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="dispo" id="flexRadioDefault1" value="0" required>
                    <label class="form-check-label" for="flexRadioDefault1">
                      Indisponible
                    </label>
                  </div>
                  <br>
                  <div class="mb-3">
                    <input type="text" class="form-control" name="comm" placeholder="Commentaires" <?php
                                                                                                    if ($dispos == NULL || $dispos == false) {
                                                                                                    } else {
                                                                                                      echo "value='" . $dispos['comm'] . "'";
                                                                                                    } ?>>
                  </div>
                  <button type="submit" name="disponi" class="btn btn-success">Ajouter sa disponibilité</button>
                </form>
                <?php if ($user['user_level'] == 2 || $user['user_level'] == 1) : ?>
                  <hr>
                  <a href="eventadmin?nom=<?php echo $nameevent ?>" class="btn btn-warning">Admin</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<?php include_once('./layouts/footer.php'); ?>