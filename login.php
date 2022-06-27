<?php
ob_start();
require_once('includes/load.php');
if ($session->isUserLoggedIn(true)) {
  redirect('index', false);
}
?>
<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Croix rouge Ixelles - Préventif" />
  <title>Se connecter - Hélio</title>
  <link rel="icon" type="image/png" href="./libs/img/helio-blanc.png" />
  <link href="./libs/css/bootstrap-icons.css" rel="stylesheet" />
  <link href="./libs/css/bootstrap.css" rel="stylesheet" />
  <link href="./libs/css/styles.css" rel="stylesheet" />
</head>

<body class="d-flex flex-column h-100">
  <main class="flex-shrink-0">

    <section class="vh-100" style="background-color: #f8f9fa;">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-2-strong" style="border-radius: 1rem;">
              <div class="card-body p-5">
                <div class="text-center">
                  <img src="./libs/img/helio-rouge.png" width="100" height="100">
                </div>
                <?php echo display_msg($msg); ?>
                <h3 class="mb-2 text-center">Se connecter</h3>
                <p class="mb-5 text-center">Hélio</p>
                <form method="post" action="auth.php" class="clearfix">
                  <div class="form-floating mb-3 text-center">
                    <input class="form-control" name="username" type="text" placeholder="Nom d'utilisateur" />
                    <label for="name">Nom d'utilisateur</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input class="form-control" name="password" type="password" placeholder="Mots de passe" id="inputmdp" />
                    <label for="name">Mots de passe</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input class="form-check-input" type="checkbox" onclick="showmdp()"> Voir le mot de passe</input>
                  </div>
                  <script>
                    function showmdp() {
                      var x = document.getElementById("inputmdp");
                      if (x.type === "password") {
                        x.type = "text";
                      } else {
                        x.type = "password";
                      }
                    }
                  </script>

                  <div class="text-center">
                    <button class="btn btn-primary btn-block" type="submit">S'identifier</button>

                    <hr class="my-4">
                </form>
                <a href="mdp-oublier">
                  <button class="btn btn-danger btn-block" type="submit"><i class="bi bi-search"></i> Mots de passe oublié</button>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </section>
    <?php include_once('layouts/footer.php'); ?>