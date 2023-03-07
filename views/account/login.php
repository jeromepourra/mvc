<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
?>

<h1 class="mb-5"><?= FrontHelper::get('body-title'); ?></h1>
<form action="<?= WEB_ROOT . 'connexion' . FrontHelper::getUrlParams(); ?>" method="post">
    <div class="mb-3">
        <?php
            FrontHelper::echoFormMessages('global', true, "fs-5 text-center");
            FrontHelper::echoFormMessages('global', false, "fs-5 text-center");
        ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="user">Pseudo ou Email</label>
        <input class="form-control" type="text" id="user" name="user" value="<?= FrontHelper::getFormInputValue('user'); ?>">
        <?php FrontHelper::echoFormMessages('user'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="password">Mot de passe</label>
        <input class="form-control" type="password" id="password" name="password">
        <?php FrontHelper::echoFormMessages('password'); ?>
    </div>
    <div>
        <input class="btn btn-primary" type="submit" value="Se connecter">
    </div>
</form>
<hr class="mt-3">
<p class="mt-3">Vous n'avez pas de compte <a href="<?= WEB_ROOT . 'inscription'; ?>">Inscrivez-vous</a></p>
