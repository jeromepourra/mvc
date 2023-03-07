<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
$oUser = Session::getObject('account', 'User');
?>

<div class="mb-5 d-flex justify-content-between">
    <h1 class="m-0"><?= FrontHelper::get('body-title'); ?></h1>
    <a class="btn btn-primary align-self-center" href="<?= WEB_ROOT . 'annonces-utilisateur/' . $oUser->_id; ?>">Voir mes annonces</a>
</div>

<form class="border border-secondary rounded mb-5 p-3" action="<?= WEB_ROOT . 'mon-compte'; ?>" method="post">
    <h2 class="text-center fs-4">Modifier votre adresse email</h2>
    <div class="mb-3">
        <?php
            FrontHelper::echoFormMessages('form-email-global', true, "fs-5 text-center");
            FrontHelper::echoFormMessages('form-email-global', false, "fs-5 text-center");
        ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="form-email-email">Nouvelle adresse email</label>
        <input class="form-control" type="email" id="form-email-email" name="form-email-email" value="<?= FrontHelper::getFormInputValue('form-email-email'); ?>">
        <?php FrontHelper::echoFormMessages('form-email-email'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="form-email-password">Mot de passe</label>
        <input class="form-control" type="password" id="form-email-password" name="form-email-password">
        <?php FrontHelper::echoFormMessages('form-email-password'); ?>
    </div>
    <div>
        <input class="btn btn-primary" type="submit" name="form-email" value="Changer mon adresse email">
    </div>
</form>

<form class="border border-secondary rounded mb-5 p-3" action="<?= WEB_ROOT . 'mon-compte'; ?>" method="post">
    <h2 class="text-center fs-4">Modifier votre mot de passe</h2>
    <div class="mb-3">
        <?php
            FrontHelper::echoFormMessages('form-password-global', true, "fs-5 text-center");
            FrontHelper::echoFormMessages('form-password-global', false, "fs-5 text-center");
        ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="form-password-old">Mot de passe actuel</label>
        <input class="form-control" type="password" id="form-password-old" name="form-password-old">
        <?php FrontHelper::echoFormMessages('form-password-old'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="form-password-new">Nouveau mot de passe</label>
        <input class="form-control" type="password" id="form-password-new" name="form-password-new">
        <?php FrontHelper::echoFormMessages('form-password-new'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="form-password-new-conf">Confirmation du nouveau mot de passe</label>
        <input class="form-control" type="password" id="form-password-new-conf" name="form-password-new-conf">
        <?php FrontHelper::echoFormMessages('form-password-new-conf'); ?>
    </div>
    <div>
        <input class="btn btn-primary" type="submit" name="form-password" value="Changer mon mot de passe">
    </div>
</form>

<form class="border border-secondary rounded p-3" action="<?= WEB_ROOT . 'mon-compte'; ?>" method="post">
    <h2 class="text-center fs-4">Supprimer mon compte</h2>
    <div class="mb-3">
        <?php
            FrontHelper::echoFormMessages('form-remove-global', true, "fs-5 text-center");
            FrontHelper::echoFormMessages('form-remove-global', false, "fs-5 text-center");
        ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="form-remove-password">Mot de passe</label>
        <input class="form-control" type="password" id="form-remove-password" name="form-remove-password">
        <?php FrontHelper::echoFormMessages('form-remove-password'); ?>
    </div>
    <div>
        <input class="btn btn-primary" type="submit" name="form-remove" value="Supprimer mon compte">
    </div>
</form>
