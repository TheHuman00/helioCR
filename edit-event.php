<?php
$page_title = 'Modifier prev';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(2);
$user = current_user();
$nomevent = $_GET['nom'];
if (!$nomevent) {
  $session->msg("d", "Nom manquante.");
  redirect('index');
}
$event = trouver_event_table($nomevent);
$evenements = join_calen_table();
?>
<?php
if (isset($_POST['add_event'])) {
  if (empty($errors)) {
    $p_nomevent = remove_junk($db->escape($nomevent));
    $p_timeeventstart  = remove_junk($db->escape($_POST['timeeventstart']));
    $p_timeeventend   = remove_junk($db->escape($_POST['timeeventend']));
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
    $query  = "UPDATE calendrier SET start='{$p_timeeventstart}', end='{$p_timeeventend}', description='{$p_description}', adresse='{$p_adresse}', personnel='{$p_personnel}', doubleprev='{$p_doubleprev}' WHERE title='{$p_nomevent}'";
    if ($db->query($query)) {
      foreach ($evenements as $evenement) {
        if ($evenement['date'] == $event['date']) {
          if ($evenement['title'] !== $nomevent) {
            $sql = "UPDATE calendrier SET doubleprev='{$p_doubleprev}' WHERE title='{$evenement['title']}'";
            if ($db->query($sql)) {
              $session->msg('s', "Evénement modifier");
            }
          }
        }
      }
      $session->msg('s', "Evénement modifier");
      redirect("eventadmin?nom=$nomevent", false);
    } else {
      $session->msg('d', ' Désolé, échec de l\'ajout!');
      redirect("eventadmin?nom=$nomevent", false);
    }
  } else {
    $session->msg("d", $errors);
    redirect("eventadmin?nom=$nomevent", false);
  }
}
$orgDate = $event['date'];
$newDate = date("d-m-Y", strtotime($orgDate));
include_once('layouts/header.php');
?>

<?php echo display_msg($msg); ?>
<section class="py-5">
  <div class="container px-2">
    <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
      <div class="text-center mb-5">
        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-calendar3-range"></i></div>
        <h1 class="fw-bolder">Modifier un préventif</h1>
        <p class="lead fw-normal text-muted mb-0"><?php echo $newDate; ?></p>
      </div>
      <div class="row gx-5 justify-content-center">
        <div class="col-lg-8 col-xl-6">
          <form method="post" action="edit-event.php?nom=<?php echo $nomevent ?>" class="clearfix">
            <div class="input-group mb-3">
              <input type="time" class="form-control" name="timeeventstart" value="<?php echo $event['start'] ?>">
              <input type="time" class="form-control" name="timeeventend" value="<?php echo $event['end'] ?>">
            </div>
            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="adresse" placeholder="Adresse de l'événement" value="<?php echo $event['adresse'] ?>">
              <label for="adres">Adresse du prev</label>
            </div>
            <div class="form-floating mb-3">
              <input type="number" class="form-control" name="personnel" placeholder="Nombre de volontaires" value="<?php echo $event['personnel'] ?>">
              <label for="vol">Nombre de volontaires</label>
            </div>
            <div class="form-floating mb-3">
              <textarea class="form-control" name="description" placeholder="Description" type="text" style="height: 10rem"><?php echo $event['description'] ?></textarea>
              <label for="message">Description</label>
            </div>
            <?php foreach ($evenements as $evenement) {
              if ($evenement['date'] == $event['date']) {
                if ($evenement['title'] !== $event['title']) {
                  $view[] = true;
                }
              }
            }
            if (!empty($view) && in_array(true, $view)) : ?>
              <div class="card shadow border-0">
                <div class="card-body">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="oui" id="flexCheckChecked" name="doubleprev" <?php if ($event['doubleprev'] == "oui") {
                                                                                                                          echo "checked";
                                                                                                                        }; ?>>
                    <label class="form-check-label" for="flexCheckChecked">
                      Autoriser le double préventif ?
                    </label>
                  </div>
                </div>
              </div>
              </br>
            <?php endif; ?>
            <div class="d-grid"><button class="btn btn-primary btn-lg" name="add_event" type="submit">Modifier</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once('layouts/footer.php'); ?>