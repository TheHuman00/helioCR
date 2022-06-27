<?php
$page_title = 'Modifier user';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(1);
$user = current_user();
?>
<?php
$e_user = find_by_id('users', (int)$_GET['id']);
$groups  = find_all('user_groups');
if (!$e_user) {
  $session->msg("d", "Pas d'user ID.");
  redirect('utilisateuradmin');
}
?>

<?php
//Update User info
if (isset($_POST['update'])) {
  $req_fields = array('username', 'level', 'email');
  validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int)$e_user['id'];
    $name = remove_junk($db->escape($_POST['username']));
    $username = remove_junk($db->escape($_POST['username']));
    $email = remove_junk($db->escape($_POST['email']));
    $telephone = remove_junk($db->escape($_POST['telephone']));
    $competence = $_POST['competence'];
    $admin = remove_junk($db->escape($_POST['admin']));
    $permis = remove_junk($db->escape($_POST['permis']));
    $cle = remove_junk($db->escape($_POST['cle']));
    $level = (int)$db->escape($_POST['level']);
    $sql = "UPDATE users SET name ='{$name}', username ='{$username}',user_level='{$level}',status='1', email='{$email}', telephone='{$telephone}', competence='{$competence}', admin='{$admin}' , permis='{$permis}' , cle='{$cle}' WHERE id='{$db->escape($id)}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Compte mis à jour ");
      redirect('utilisateuradmin', false);
    } else {
      $session->msg('d', ' Désolé, échec de la mise à jour!');
      redirect('edit_user?id=' . (int)$e_user['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_user?id=' . (int)$e_user['id'], false);
  }
}
?>
<?php
// Update user password
if (isset($_POST['update-pass'])) {
  $req_fields = array('password');
  validate_fields($req_fields);
  if (empty($errors)) {
    $id = (int)$e_user['id'];
    $password = remove_junk($db->escape($_POST['password']));
    $h_pass   = password_hash($password, PASSWORD_DEFAULT, ["cost" => 12]);
    $sql = "UPDATE users SET password='{$h_pass}' WHERE id='{$db->escape($id)}'";
    $result = $db->query($sql);
    if ($result && $db->affected_rows() === 1) {
      $session->msg('s', "Le mot de passe de l'utilisateur a été mis à jour ");
      redirect('edit_user?id=' . (int)$e_user['id'], false);
    } else {
      $session->msg('d', ' Désolé, échec de la mise à jour du mot de passe de l\'utilisateur!');
      redirect('edit_user?id=' . (int)$e_user['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_user?id=' . (int)$e_user['id'], false);
  }
}

?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
  <div class="container px-2">
    <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
      <div class="text-center mb-5">
        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-envelope"></i></div>
        <h1 class="fw-bolder">Mettre à jour le compte de <?php echo remove_junk(ucwords($e_user['name'])); ?></h1>
        <p class="lead fw-normal text-muted mb-0">Edition</p>
      </div>
      <div class="row gx-5 justify-content-center">
        <div class="col-lg-8 col-xl-6">
          <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id']; ?>" class="clearfix">
            <input type="hidden" name="username" type="text" placeholder="Prénom" value="<?php echo remove_junk(ucwords($e_user['username'])); ?>" />
            <div class="form-floating mb-3">
              <input class="form-control" name="email" type="email" placeholder="nom@exemple.com" value="<?php echo remove_junk(ucwords($e_user['email'])); ?>" required />
              <label for="email">Adresse Email</label>
            </div>
            <div class="form-floating mb-3">
              <input class="form-control" name="telephone" type="text" value="<?php echo remove_junk(ucwords($e_user['telephone'])); ?>" required />
              <label for="telephone">Numéro de Téléphone (+32)</label>
            </div>
            <select class="form-select" name="level" required>
              <?php foreach ($groups as $group) : ?>
                <option <?php if ($group['group_level'] === $e_user['user_level']) echo 'selected="selected"'; ?> value="<?php echo $group['group_level']; ?>"><?php echo ucwords($group['group_name']); ?></option>
              <?php endforeach; ?>
            </select>
            <hr>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="beps" <?php if (str_contains($e_user['competence'], "beps") == true) {
                                                                                            echo "checked";
                                                                                          } ?>>
              <label class="form-check-label" for="inlineCheckbox1">BEPS</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="secouriste" <?php if (str_contains($e_user['competence'], "secouriste") == true) {
                                                                                                  echo "checked";
                                                                                                } ?>>
              <label class="form-check-label" for="inlineCheckbox2">Secouriste</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="105" <?php if (str_contains($e_user['competence'], "105") == true) {
                                                                                            echo "checked";
                                                                                          } ?>>
              <label class="form-check-label" for="inlineCheckbox3">Ambu 105</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="amu2" <?php if (str_contains($e_user['competence'], "amu") == true) {
                                                                                            echo "checked";
                                                                                          } ?>>
              <label class="form-check-label" for="inlineCheckbox1">AMU</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="infi2" <?php if (str_contains($e_user['competence'], "infi") == true) {
                                                                                              echo "checked";
                                                                                            } ?>>
              <label class="form-check-label" for="inlineCheckbox2">Infi</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="infisiamu" <?php if (str_contains($e_user['competence'], "infisiamu") == true) {
                                                                                                  echo "checked";
                                                                                                } ?>>
              <label class="form-check-label" for="inlineCheckbox3">Infi SIAMU</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="medecin" <?php if (str_contains($e_user['competence'], "medecin") == true) {
                                                                                                echo "checked";
                                                                                              } ?>>
              <label class="form-check-label" for="inlineCheckbox3">Medecin</label>
            </div>
            <hr>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="admin" value="true" <?php if ($e_user['admin'] == true) {
                                                                                          echo "checked";
                                                                                        } ?>>
              <label class="form-check-label" for="inlineCheckbox3">Membre du comité</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="permis" value="true" <?php if ($e_user['permis'] == "true") {
                                                                                            echo "checked";
                                                                                          } ?>>
              <label class="form-check-label" for="inlineCheckbox3">Chauffeur Section-Locale</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="cle" value="true" <?php if ($e_user['cle'] == "true") {
                                                                                        echo "checked";
                                                                                      } ?>>
              <label class="form-check-label" for="inlineCheckbox3">Clé Section</label>
            </div>
            <br>
            <br>
            <div class="d-grid"><button class="btn btn-primary btn-lg" name="update" type="submit">Mettre à jour</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="bg-light py-5">
  <div class="container px-2">
    <div class="rounded-3 py-5 px-4 px-md-5 mb-5">
      <div class="row gx-5 justify-content-center">
        <div class="col-lg-8 col-xl-6">
          <form action="edit_user.php?id=<?php echo (int)$e_user['id']; ?>" method="post" class="clearfix">
            <div class="form-floating mb-3">
              <input class="form-control" name="password" type="text" placeholder="Nouveau mots de passe" required />
              <label for="name">Nouveau mots de passe</label>
            </div>
            <div class="d-grid"><button class="btn btn-primary btn-lg" name="update-pass" type="submit">Changer mots de passe</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include_once('layouts/footer.php'); ?>