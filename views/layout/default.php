<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
?>

<?php
$bUserConnected = Session::get('account', 'connected') === true;
$sControllerName = FrontHelper::get('controller', 'name');
$sHeadTitle = FrontHelper::get('head-title') !== null ? FrontHelper::get('head-title') : Constants::WEB_SITE_NAME;
$sHeadDescription = FrontHelper::get('head-desc') !== null ? FrontHelper::get('head-desc') : Constants::WEB_SITE_DESCRIPTION_DEFAULT;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $sHeadDescription; ?>">
    <link rel="stylesheet" href="<?= WEB_ROOT . 'views/public/css/style.css' ?>">
    <title><?= $sHeadTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= WEB_ROOT . 'views/public/css/style.css' ?>">
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg text-body bg-body" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= WEB_ROOT; ?>"><?= Constants::WEB_SITE_NAME ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?= $sControllerName == 'CreateAnnonce' ? 'active' : '' ?>" href="<?= WEB_ROOT . 'deposer-une-annonce'; ?>">Déposer une annonce</a>
                        </li>
                        <?php if (!$bUserConnected) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $sControllerName == 'Login' ? 'active' : '' ?>" href="<?= WEB_ROOT . 'connexion'; ?>">Connexion</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $sControllerName == 'Register' ? 'active' : '' ?>" href="<?= WEB_ROOT . 'inscription'; ?>">Inscription</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $sControllerName == 'MyAccount' ? 'active' : '' ?>" href="<?= WEB_ROOT . 'mon-compte'; ?>">Mon compte</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $sControllerName == 'Logout' ? 'active' : '' ?>" href="<?= WEB_ROOT . 'deconnexion'; ?>">Deconnexion</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container my-5">
        <?= $view_content ?>
    </main>

    <footer class="container-fluid text-body bg-body" data-bs-theme="dark">
        <p class="text-center m-0 py-1">Lorem, ipsum dolor.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

</body>

</html>