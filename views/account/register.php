<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
?>

<h1 class="mb-5"><?= FrontHelper::get('body-title'); ?></h1>
<form action="<?= WEB_ROOT . 'inscription' . FrontHelper::getUrlParams(); ?>" method="post">
    <div class="global-infos mb-3">
        <?php
            FrontHelper::echoFormMessages('global', true, "fs-5 text-center");
            FrontHelper::echoFormMessages('global', false, "fs-5 text-center");
        ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="pseudo">Pseudo</label>
        <input class="form-control" type="text" id="pseudo" name="pseudo" value="<?= FrontHelper::getFormInputValue('pseudo'); ?>">
        <?php FrontHelper::echoFormMessages('pseudo'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="firstname">Prénom</label>
        <input class="form-control" type="text" id="firstname" name="firstname" value="<?= FrontHelper::getFormInputValue('firstname'); ?>">
        <?php FrontHelper::echoFormMessages('firstname'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="lastname">Nom</label>
        <input class="form-control" type="text" id="lastname" name="lastname" value="<?= FrontHelper::getFormInputValue('lastname'); ?>">
        <?php FrontHelper::echoFormMessages('lastname'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" type="email" id="email" name="email" value="<?= FrontHelper::getFormInputValue('email'); ?>">
        <?php FrontHelper::echoFormMessages('email'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="phone">Numéro de téléphone</label>
        <input class="form-control" type="tel" id="phone" name="phone" value="<?= FrontHelper::getFormInputValue('phone'); ?>">
        <?php FrontHelper::echoFormMessages('phone'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="birthday">Date de naissance</label>
        <input class="form-control" type="date" id="birthday" name="birthday" value="<?= FrontHelper::getFormInputValue('birthday'); ?>">
        <?php FrontHelper::echoFormMessages('birthday'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="password">Mot de passe</label>
        <input class="form-control" type="password" id="password" name="password">
        <?php FrontHelper::echoFormMessages('password'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="passwordconf">Confirmation du mot de passe</label>
        <input class="form-control" type="password" id="passwordconf" name="passwordconf">
        <?php FrontHelper::echoFormMessages('passwordconf'); ?>
    </div>
    <div class="mb-0">
        <div class="form-check form-switch">
            <input type="hidden" name="dataconfmajor" value="off">
            <input class="form-check-input" type="checkbox" id="dataconfmajor" name="dataconfmajor">
            <label class="form-check-label" for="dataconfmajor">Je certifie être majeur.</label>
            <?php FrontHelper::echoFormMessages('dataconfmajor', false, "text-start"); ?>
        </div>
    </div>
    <div class="mb-3">
        <div class="form-check form-switch">
            <input type="hidden" name="dataconfinfos" value="off">
            <input class="form-check-input" type="checkbox" id="dataconfinfos" name="dataconfinfos">
            <label class="form-check-label" for="dataconfinfos">Je certifie que les informations remplies dans le formulaire ci-dessus sont exactes.</label>
            <?php FrontHelper::echoFormMessages('dataconfinfos', false, "text-start"); ?>
        </div>
    </div>
    <div>
        <input class="btn btn-primary" type="submit" value="S'inscrire">
    </div>
</form>
<hr class="mt-3">
<p class="mt-3">Vous avez déjà un compte <a href="<?= WEB_ROOT . 'connexion' . FrontHelper::getUrlParams(); ?>">Connectez-vous</a></p>
