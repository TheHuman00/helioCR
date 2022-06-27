<?php include_once('includes/load.php');
$req_fields = array('username', 'password');
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if (empty($errors)) {
  $user_id = authenticate($username, $password);
  if ($user_id) {
    $session->login($user_id);
    redirect('index', false);
  } else {
    $session->msg("d", "Désolé nom d'utilisateur / mot de passe incorrect.");
    redirect('login', false);
  }
} else {
  $session->msg("d", $errors);
  redirect('login', false);
}

?>
