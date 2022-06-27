<?php
$page_title = 'Ajouter user';
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index', false);
}
page_require_level(1);
$user = current_user();
$groups = find_all('user_groups');
$database = new MySqli_DB();
?>
<?php
if (isset($_POST['add_user'])) {

  if (empty($errors)) {
    $nametrim2 = str_replace("é", "e", $_POST['username']);
    $nametrim = str_replace("è", "e", $nametrim2);
    $name   = remove_junk($db->escape($nametrim));
    $username   = remove_junk($db->escape($nametrim));
    $password2   = remove_junk($db->escape($_POST['password']));
    $telephone   = remove_junk($db->escape($_POST['telephone']));
    $competence = $_POST['competence'];
    $admin = remove_junk($db->escape($_POST['admin']));
    $permis = remove_junk($db->escape($_POST['permis']));
    $cle = remove_junk($db->escape($_POST['cle']));
    $email   = remove_junk($db->escape($_POST['email']));
    $user_level = (int)$db->escape($_POST['level']);
    $password = password_hash($password2, PASSWORD_DEFAULT, ["cost" => 12]);
    $query = $database->db_prepare("INSERT INTO users (name,username,password,user_level,status,email,telephone,competence,admin,permis,cle) VALUES (?,?,?,?,'1',?,?,?,?,?,?)");
    $query->bind_param("ssssssssss", $name, $username, $password, $user_level, $email, $telephone, $competence, $admin, $permis, $cle);
    if ($query->execute()) {
      $session->msg('s', "Ajout de l'utilisateur réussi");
      redirect('utilisateuradmin', false);
    } else {
      $session->msg('d', "Ajout de l'utilisateur échoué");
      redirect('utilisateuradmin', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('utilisateuradmin', false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<section class="py-5">
  <div class="container px-2">
    <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
      <div class="text-center mb-5">
        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-person-plus"></i></div>
        <h1 class="fw-bolder">Ajouter un utilisateur</h1>
        <p class="lead fw-normal text-muted mb-0">Un nouveau ?</p>
      </div>
      <div class="row gx-5 justify-content-center">
        <div class="col-lg-8 col-xl-6">
          <form method="post" action="ajout_utilisateur.php">
            <div class="form-floating mb-3">
              <input class="form-control" name="username" type="text" placeholder="Prénom" required />
              <label for="name">Nom d'utilisateur</label>
            </div>
            <div class="form-floating mb-3">
              <input class="form-control" name="email" type="email" placeholder="nom@exemple.com" required />
              <label for="email">Adresse Email</label>
            </div>
            <div class="form-floating mb-3">
              <input class="form-control" name="telephone" type="text" value="+32" required />
              <label for="telephone">Numéro de Téléphone (+32)</label>
            </div>
            <div class="form-floating mb-3">
              <input class="form-control" name="password" type="text" placeholder="Mots de passe" required />
              <label for="phone">Mots de passe</label>
            </div>
            <select class="form-select" name="level" required>
              <option selected>Niveau de permission</option>
              <?php foreach ($groups as $group) : ?>
                <option value="<?php echo $group['group_level']; ?>"><?php echo ucwords($group['group_name']); ?></option>
              <?php endforeach; ?>
            </select>
            <hr>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="beps">
              <label class="form-check-label" for="inlineCheckbox1">BEPS</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="secouriste">
              <label class="form-check-label" for="inlineCheckbox2">Secouriste</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="105">
              <label class="form-check-label" for="inlineCheckbox3">Ambu 105</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="amu2">
              <label class="form-check-label" for="inlineCheckbox1">AMU</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="infi2">
              <label class="form-check-label" for="inlineCheckbox2">Infi</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="infisiamu">
              <label class="form-check-label" for="inlineCheckbox3">Infi SIAMU</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="competence" value="medecin">
              <label class="form-check-label" for="inlineCheckbox3">Medecin</label>
            </div>
            <hr>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="admin" value="true">
              <label class="form-check-label" for="inlineCheckbox3">Membre du comité</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="permis" value="true">
              <label class="form-check-label" for="inlineCheckbox3">Chauffeur Section-Locale</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="cle" value="true">
              <label class="form-check-label" for="inlineCheckbox3">Clé Section</label>
            </div>
            <br>
            <br>
            <div class="d-grid"><button class="btn btn-primary btn-lg" name="add_user" type="submit">Ajouter</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>












<?php include_once('layouts/footer.php'); ?>