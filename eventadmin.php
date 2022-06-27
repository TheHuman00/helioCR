<?php
$page_title = 'Prev admin';
require_once('includes/load.php');
$user = current_user();
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(2);
$users = join_user_table2();
$all_historique = find_all('historique');
require __DIR__ . '/vendor/autoload.php';

use \Ovh\Api;
?>
<?php
$nameevent = $_GET['nom'];
$disponibilites = join_dispo_table();
$calendrier = find_event_by_title($nameevent);
$evenements = join_calen_table();
?>
<?php
$date2 = $nameevent;
$date = explode("DATE", $date2);
$datecal = $date['1'];
if (isset($_POST['disponi'])) {
  if (empty($errors)) {
    $p_dispo   = remove_junk($db->escape($_POST['dispo']));
    $query2  = "INSERT INTO disponibilite (";
    $query2 .= "user,event,date,dispo";
    $query2 .= ") VALUES (";
    $query2 .= "'{$user['name']}', '{$nameevent}', '{$date['1']}' '{$p_dispo}'";
    $query2 .= ") ON DUPLICATE KEY UPDATE user ='{$user['name']}', event ='{$nameevent}', date='{$date['1']}', dispo ='{$p_dispo}'";
    $result2 = $db->query($query2);
    if ($result2) {
      $session->msg('s', "Demande de participation faites  ");
      redirect("eventuser?nom=$nameevent");
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

$orgDate = $calendrier['date'];
$newDate = date("d-m-Y", strtotime($orgDate));


foreach ($disponibilites as $disponibilite) {
  if ($disponibilite['event'] == $calendrier['title']) {
    if ($disponibilite['rolecp'] == "oui") {
      $view3[] = true;
    }
    if ($disponibilite['rolechau'] == "oui") {
      $view4[] = true;
    }
  }
}

?>

<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
  <div class="container px-4">
    <h1 class="fw-bolder fs-5 mb-4">Préventif : </h1>
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
                <iframe width="100%" height="100%" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo $calendrier['adresse'] ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" allow="fullscreen"></iframe>
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
        <?php foreach ($all_historique as $historique) {
          if ($historique['event'] == $nameevent) {
            if ($historique['lu'] == "0") {
              if ($historique['dispostatus'] == "1" || $historique['dispostatus'] == "0") {
                $view[] = true;
              }
            }
          }
        }
        if (!empty($view) && in_array(true, $view)) : ?>
          <div class="card shadow border-0">
            <div class="card-body">
              <h5 class="card-title mb-3">Historique :</h5>
              <?php foreach ($all_historique as $historique) :
                if ($historique['event'] == $nameevent) :
                  if ($historique['lu'] == "0") : ?>
                    <li class="mb-0" <?php if ($historique['dispostatus'] == "0") {
                                        echo "style=\"color:red\"";
                                      };
                                      if ($historique['dispostatus'] == "1") {
                                        echo "style=\"color:orange\"";
                                      } ?>> <strong><?php echo $historique['user'] ?></strong> est <strong><?php if ($historique['dispostatus'] == "0") {
                                                                                                              echo "indisponible";
                                                                                                            };
                                                                                                            if ($historique['dispostatus'] == "1") {
                                                                                                              echo "disponible";
                                                                                                            } ?> </strong>
                      <a href="lu_historique?user=<?php echo $historique['user'] ?>&event=<?php echo $nameevent ?>" class="btn btn-danger btn-sm" title="Lu" data-toggle="tooltip">
                        <span class="bi bi-trash"></span>
                      </a>
                    </li>
              <?php endif;
                endif;
              endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
        <br>
        <h2 class="fw-bolder fs-5 mb-4">Effectifs :</h2>
        <h5>Personnes présentes (acceptées): </h5>
        <?php foreach ($disponibilites as $disponibilite) : ?>
          <?php if ($disponibilite['event'] == $nameevent) : ?>
            <?php if ($disponibilite['dispo'] == "2") : ?>
              <ul style="color:green"> - <?php echo $disponibilite['user'] ?> <?php if ($disponibilite['comm'] !== "") {
                                                                                echo "(" . $disponibilite['comm'] . ")";
                                                                              } ?>
                <a href="refuserevent?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>" class="btn btn-danger btn-sm" title="Refuser" data-toggle="tooltip">
                  <span class="bi bi-x-lg"></span>
                </a>
                <?php if (!empty($view3) && in_array(true, $view3)) : ?>
                  <?php if ($disponibilite['rolecp'] == "oui") : ?>
                    <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=xcp" class="btn btn-sm" title="Chef poste" data-toggle="tooltip">
                      <i class="bi bi-trash">👑</i>
                    </a>
                  <?php endif; ?>
                <?php else : ?>
                  <?php if ($disponibilite['rolecp'] == "oui") : ?>
                    <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=xcp" class="btn btn-secondary btn-sm" title="Chef poste" data-toggle="tooltip">
                      <i class="bi bi-trash">👑</i>
                    </a>
                  <?php else : ?>
                    <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=cp" class="btn btn-secondary btn-sm" title="Chef poste" data-toggle="tooltip">
                      <i>👑</i>
                    </a>
                  <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($view4) && in_array(true, $view4)) : ?>
                  <?php if ($disponibilite['rolechau'] == "oui") : ?>
                    <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=xchau" class="btn btn-sm" title="Chauffeur" data-toggle="tooltip">
                      <i class="bi bi-trash">🚗</i>
                    </a>
                  <?php endif; ?>
                <?php else : ?>
                  <?php if ($disponibilite['rolechau'] == "oui") : ?>
                    <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=xchau" class="btn btn-secondary btn-sm" title="Chauffeur" data-toggle="tooltip">
                      <i class="bi bi-trash">🚗</i>
                    </a>
                  <?php else : ?>
                    <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=chau" class="btn btn-secondary btn-sm" title="Chauffeur" data-toggle="tooltip">
                      <i>🚗</i>
                    </a>
                  <?php endif; ?>
                <?php endif; ?>
              </ul>

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
                                                                                  } ?>
                  <a href="acceptevent?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>" class="btn btn-success btn-sm" title="Accepter" data-toggle="tooltip">
                    <span class="bi bi-check-lg"></span>
                  </a>
                  <a href="refuserevent?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>" class="btn btn-danger btn-sm" title="Refuser" data-toggle="tooltip">
                    <span class="bi bi-x-lg"></span>
                  </a>


                  <?php if (!empty($view3) && in_array(true, $view3)) : ?>
                    <?php if ($disponibilite['rolecp'] == "oui") : ?>
                      <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=xcp" class="btn btn-sm" title="Chef poste" data-toggle="tooltip">
                        <i class="bi bi-trash">👑</i>
                      </a>
                    <?php endif; ?>
                  <?php else : ?>
                    <?php if ($disponibilite['rolecp'] == "oui") : ?>
                      <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=xcp" class="btn btn-secondary btn-sm" title="Chef poste" data-toggle="tooltip">
                        <i class="bi bi-trash">👑</i>
                      </a>
                    <?php else : ?>
                      <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=cp" class="btn btn-secondary btn-sm" title="Chef poste" data-toggle="tooltip">
                        <i>👑</i>
                      </a>
                    <?php endif; ?>
                  <?php endif; ?>

                  <?php if (!empty($view4) && in_array(true, $view4)) : ?>
                    <?php if ($disponibilite['rolechau'] == "oui") : ?>
                      <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=xchau" class="btn btn-sm" title="Chef poste" data-toggle="tooltip">
                        <i class="bi bi-trash">🚗</i>
                      </a>
                    <?php endif; ?>
                  <?php else : ?>
                    <?php if ($disponibilite['rolechau'] == "oui") : ?>
                      <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=xchau" class="btn btn-secondary btn-sm" title="Chef poste" data-toggle="tooltip">
                        <i class="bi bi-trash">🚗</i>
                      </a>
                    <?php else : ?>
                      <a href="addrole?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>&role=chau" class="btn btn-secondary btn-sm" title="Chef poste" data-toggle="tooltip">
                        <i>🚗</i>
                      </a>
                    <?php endif; ?>
                  <?php endif; ?>


                </ul>
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
                                                                              } ?>
                  <a href="acceptevent?user=<?php echo $disponibilite['user'] ?>&event=<?php echo $nameevent ?>" class="btn btn-success btn-sm" title="Accepter" data-toggle="tooltip">
                    <span class="bi bi-check-lg"></span>
                  </a>
                </ul>
              <?php endif; ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <?php foreach ($disponibilites as $disponibilite) {
          if ($disponibilite['event'] !== $calendrier['title']) {
            if ($disponibilite['date'] == $calendrier['date']) {
              if ($disponibilite['dispo'] == "1" || $disponibilite['dispo'] == "2") {
                $view2[] = true;
              }
            }
          }
        }
        if (!empty($view2) && in_array(true, $view2)) : ?>
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
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-people"></i></div>
                <div class="h6 fw-bolder">Administration</div>
                <form method="post" action="accepteventpost.php?event=<?php echo $nameevent ?>" class="clearfix">
                  <div class="mb-3">
                    <select class="form-select" id="inputGroupSelect03" name="nomuser" saria-label="ajouter">
                      <option selected>Volontaires</option>
                      <?php foreach ($users as $user) : ?>
                        <option value="<?php echo $user["name"] ?>"><?php echo $user["name"] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <input type="text" class="form-control" name="comm" placeholder="Commentaires">
                  </div>
                  <button type="submit" class="btn btn-outline-secondary" name="att" value="true" href="accepteventpost?event=<?php echo $nameevent ?>" id="chercher" style="color:orange">Ajouter</button>
                  <button type="submit" class="btn btn-outline-secondary" name="att" value="false" href="accepteventpost?event=<?php echo $nameevent ?>" id="chercher" style="color:green">Ajouter</button>
                </form>
                <hr>
                <div class="input-group mb-3">
                  <a href="demandeperso?nom=<?php echo $nameevent ?>" type="button" class="btn btn-primary">Envoyer E-MAIL</a>

                  <a href="demandepersosms?nom=<?php echo $nameevent ?>" type="button" class="btn btn-primary">Envoyer SMS <?php echo $pricesms ?>€</a>
                </div>
                <a href="edit-event?nom=<?php echo $nameevent ?>" type="button" class="btn btn-warning">Modifier</a>
                <br>
                <br>
                <a href="supevent?nom=<?php echo $nameevent ?>" type="button" class="btn btn-danger">Supprimer l'événement</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
  </div>
</section>
<footer class="bg-dark py-4 mt-auto">
  <div class="container px-5">
    <div class="row align-items-center justify-content-between flex-column flex-sm-row">
      <div class="col-auto">
        <div class="small m-0 text-white">Copyright &copy; Helio 2021</div>
      </div>
      <div class="col-auto">
        <span class="text-white mx-1">&middot;</span>
        <a class="link-light small" href="./credits">Crédits</a>
        <span class="text-white mx-1">&middot;</span>
      </div>
    </div>
  </div>
</footer>
<script src="./libs/js/bootstrap.bundle.min.js"></script>
</body>

</html>