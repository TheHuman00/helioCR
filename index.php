<?php
require_once('includes/load.php');
$user = current_user();
if (!$session->isUserLoggedIn(true)) {
    redirect('login', false);
}
if (!empty($_GET['date'])) {
    $inidate = htmlentities($_GET['date']);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Croix rouge ixelles - Préventif" />
    <title>Accueil - Hélio</title>
    <link rel="icon" type="image/png" href="./libs/img/helio-blanc.png" />
    <link href="./libs/css/bootstrap-icons.css" rel="stylesheet" />
    <link href="./libs/css/styles.css" rel="stylesheet" />
    <link href='./libs/css/bootstrap.css' rel='stylesheet' />
    <link href='./fullcalendar/main.css' rel='stylesheet' />
    <script src='./fullcalendar/main.js'></script>
    <script src='./fullcalendar/locales/fr.js'></script>
    <?php if ($user['user_level'] == 1 || $user['user_level'] == 2) : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    allDaySlot: false,
                    weekNumbers: true,
                    <?php if (!empty($_GET['date'])) {
                        echo "initialDate: '{$inidate}',";
                    } ?>
                    weekText: "",
                    height: 900,
                    headerToolbar: {
                        start: 'title',
                        center: '',
                        end: 'today prev,next'
                    },
                    views: {
                        listMonth2: {
                            type: 'listYear',
                            buttonText: 'Liste'
                        }
                    },
                    events: 'loadcalenadmin.php',
                    eventColor: '#db3434',
                    businessHours: {
                        daysOfWeek: [1, 2, 3, 4, 5],
                        startTime: '0:00',
                        endTime: '24:00',
                    },
                    dateClick: function(info) {

                        window.location = "add_event?date=" + info.dateStr;

                    },
                    eventClick: function(info) {
                        titleevent = info.event.title;
                        timedate = info.event.start;
                        var date = ('0' + timedate.getDate()).slice(-2);
                        var month = ('0' + (timedate.getMonth() + 1)).slice(-2);
                        var year = timedate.getFullYear();
                        var titledate = year + "-" + month + "-" + date;
                        window.location = "eventadmin?nom=" + titleevent + "DATE" + titledate;
                    }
                });
                calendar.render();
                calendar.setOption('locale', 'fr');
            });
        </script>
    <?php else : ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    allDaySlot: false,
                    weekNumbers: true,
                    weekText: "",
                    height: 900,
                    <?php if (!empty($_GET['date'])) {
                        echo "initialDate: '{$inidate}',";
                    } ?>
                    headerToolbar: {
                        start: 'title',
                        center: '',
                        end: 'today prev,next'
                    },
                    views: {
                        listMonth2: {
                            type: 'listMonth',
                            buttonText: 'Liste'
                        }
                    },
                    businessHours: {
                        daysOfWeek: [1, 2, 3, 4, 5],
                        startTime: '0:00',
                        endTime: '24:00',
                    },
                    events: 'loadcalenuser.php',
                    eventColor: '#db3434',
                    eventClick: function(info) {
                        titleevent = info.event.title;
                        timedate = info.event.start;
                        var date = ('0' + timedate.getDate()).slice(-2);
                        var month = ('0' + (timedate.getMonth() + 1)).slice(-2);
                        var year = timedate.getFullYear();
                        var titledate = year + "-" + month + "-" + date;
                        window.location = "eventuser?nom=" + titleevent + "DATE" + titledate;
                    }

                });
                calendar.render();
                calendar.setOption('locale', 'fr');
            });
        </script>
    <?php endif; ?>
</head>

<body class="d-flex flex-column h-100">
    <main class="flex-shrink-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container px-6">
                <a href="index">
                    <img class="navbar-brand" src="./libs/img/helio-blanc.png" height="60" wight="60">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index">Accueil</a></li>
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
        <section class="py-3">
            <div class="container">
                <div class="text-center mb-5">
                    <h1 class="fw-bolder">Préventifs</h1>
                    <p class="lead fw-normal text-muted mb-0">Croix-Rouge Ixelles</p>
                    <div id='calendar'></div>
                </div>
                <div id='calendar'></div>
                <div class="row gx-5">
                </div>
        </section>
    </main>
    <footer class="bg-dark py-4 mt-auto">
        <div class="container px-5">
            <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                <div class="col-auto">
                    <div class="small m-0 text-white">Copyright &copy; Helio 2021</div>
                </div>
                <div class="col-auto">
                    <span class="text-white mx-1">&middot;</span>
                    <a class="link-light small" href="./credits.php">Crédits</a>
                    <span class="text-white mx-1">&middot;</span>
                </div>
            </div>
        </div>
    </footer>
    <script src="./libs/js/bootstrap.bundle.min.js"></script>
</body>

</html>