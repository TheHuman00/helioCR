<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Croix rouge Ixelles - Préventif" />
    <title><?php if (!empty($page_title)) {
                echo $page_title . " - Hélio";
            } else {
                echo "Hélio";
            } ?></title>
    <link rel="icon" type="image/png" href="./libs/img/helio-blanc.png" />
    <link href="./libs/css/bootstrap-icons.css" rel="stylesheet" />
    <link href="./libs/css/bootstrap.css" rel="stylesheet">
    <link href="./libs/css/styles.css" rel="stylesheet" />
</head>

<body class="d-flex flex-column h-100">
    <main class="flex-shrink-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-6">
                <a href="index<?php if (!empty($datecal)) {
                                        echo "?date=" . $datecal;
                                    } ?>">
                    <img class="navbar-brand" src="./libs/img/helio-blanc.png" height="60" wight="60" alt="Helio">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index<?php if (!empty($datecal)) {
                                                                                    echo "?date=" . $datecal;
                                                                                } ?>">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link" href="prev-att">Liste Préventifs</a></li>
                        <?php if ($user['user_level'] == 1 || $user['user_level'] == 2) : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdownBlog" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Volontaires</a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownBlog">
                                    <li><a class="dropdown-item" href="utilisateuradmin">Gestion volontaires</a></li>
                                    <li><a class="dropdown-item" href="view_alluser">Voir les statistiques</a></li>
                                    <li><a class="dropdown-item" href="ajout_utilisateur">Ajouter volontaires</a></li>
                                </ul>
                            </li>
                        <?php else : ?>
                            <li class="nav-item"><a class="nav-link" href="utilisateur">Volontaires</a></li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdownBlog" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Profil</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownBlog">
                                <li><a class="dropdown-item" href="icsurl">Exporter calendrier</a></li>
                                <li><a class="dropdown-item" href="changer_mdp">Changer de mots de passe</a></li>
                                <li><a class="dropdown-item" href="logout">Déconnexion</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>