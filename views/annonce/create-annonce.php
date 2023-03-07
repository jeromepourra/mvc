<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
?>

<h1 class="mb-5"><?= FrontHelper::get('body-title'); ?></h1>
<form action="<?= WEB_ROOT . 'deposer-une-annonce' ?>" method="post" enctype="multipart/form-data">
    <div class="global-infos mb-3">
        <?php
        FrontHelper::echoFormMessages('global', true, "fs-5 text-center");
        FrontHelper::echoFormMessages('global', false, "fs-5 text-center");
        ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="category">Catégorie</label>
        <select class="form-select" name="category" id="category">
            <option value="" selected>Selectionner</option>
            <?php foreach (FrontHelper::get('Categories') as $oCategory) : ?>
                <?php
                $nCategoryId = FrontHelper::getFrom($oCategory, '_id');
                $sCategoryName = FrontHelper::getFrom($oCategory, 'name');
                ?>
                <option value="<?= $nCategoryId; ?>"><?= $sCategoryName; ?></option>
            <?php endforeach; ?>
        </select>
        <?php FrontHelper::echoFormMessages('category'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="title">Titre</label>
        <input class="form-control" type="text" id="title" name="title" value="<?= FrontHelper::getFormInputValue('title'); ?>">
        <?php FrontHelper::echoFormMessages('title'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="price">Prix</label>
        <input class="form-control" type="number" id="price" name="price" step="0.01" min="0" value="<?= FrontHelper::getFormInputValue('price'); ?>">
        <?php FrontHelper::echoFormMessages('price'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" name="description" id="description" cols="30" rows="5"><?= FrontHelper::getFormInputValue('description'); ?></textarea>
        <?php FrontHelper::echoFormMessages('description'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="pictures">Selectionner une ou plusieurs images</label>
        <input class="form-control" type="file" name="pictures[]" id="pictures" accept="image/gif, image/jpeg, image/png" multiple>
        <?php FrontHelper::echoFormMessages('pictures'); ?>
    </div>
    <div>
        <input class="btn btn-primary" type="submit" value="Déposer l'annonce">
        <a class="btn btn-danger" href="<?= WEB_ROOT; ?>">Annuler</a>
    </div>
</form>