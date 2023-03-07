<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
$oAnnonce = FrontHelper::get('Annonces', 'Annonce');
$nAnnonceId = FrontHelper::getFrom($oAnnonce, '_id');
$aPictures = FrontHelper::get('Annonces', 'Pictures');
?>

<h1 class="mb-5"><?= FrontHelper::get('body-title'); ?></h1>
<form action="<?= WEB_ROOT . 'modifier-annonce/' . $nAnnonceId ?>" method="post" enctype="multipart/form-data">
    <div class="global-infos mb-3">
        <?php
        FrontHelper::echoFormMessages('global', true, "fs-5 text-center");
        FrontHelper::echoFormMessages('global', false, "fs-5 text-center");
        ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="category">Catégorie</label>
        <select class="form-select" name="category" id="category">
            <?php foreach (FrontHelper::get('Categories') as $oCategory) : ?>
                <?php
                $nCategoryId = FrontHelper::getFrom($oCategory, '_id');
                $sCategoryName = FrontHelper::getFrom($oCategory, 'name');
                $bSelected = FrontHelper::getFrom($oAnnonce, 'category_id') == $nCategoryId;
                ?>
                <option <?= ($bSelected ? 'selected' : ''); ?> value="<?= $nCategoryId; ?>"><?= $sCategoryName; ?></option>
            <?php endforeach; ?>
        </select>
        <?php FrontHelper::echoFormMessages('category'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="title">Titre</label>
        <input class="form-control" type="text" id="title" name="title" value="<?= FrontHelper::getFrom($oAnnonce, 'title'); ?>">
        <?php FrontHelper::echoFormMessages('title'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="price">Prix</label>
        <input class="form-control" type="number" id="price" name="price" step="0.01" min="0" value="<?= FrontHelper::getFrom($oAnnonce, 'price'); ?>">
        <?php FrontHelper::echoFormMessages('price'); ?>
    </div>
    <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" name="description" id="description" cols="30" rows="5"><?= FrontHelper::getFrom($oAnnonce, 'description'); ?></textarea>
        <?php FrontHelper::echoFormMessages('description'); ?>
    </div>
    <div class="mb-3">
        <div class="container">
            <div class="row">
                <?php foreach ($aPictures as $oPicture) : ?>
                    <?php
                    $nPictureId = FrontHelper::getFrom($oPicture, '_id');
                    $sPictureExt = FrontHelper::getFrom($oPicture, 'extension');
                    ?>
                    <div class="col mb-3">
                        <img class="d-block m-auto rounded mb-1" src="<?= WEB_ROOT . Constants::PATH_ANNONCES . str_pad($nAnnonceId, Constants::ANNONCE_PICTURE_DIRECTORY_NAME_LENGTH, "0", STR_PAD_LEFT) . '/' . $nPictureId . '.' . $sPictureExt; ?>" alt="*" width="150">
                        <a class="d-block btn btn-primary" href="<?= WEB_ROOT . 'modifier-annonce/' . $nAnnonceId . '/supprimer-image/' . $nPictureId; ?>">Retirer l'image</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="pictures">Selectionner une ou plusieurs images</label>
        <input class="form-control" type="file" name="pictures[]" id="pictures" accept="image/gif, image/jpeg, image/png" multiple>
        <?php FrontHelper::echoFormMessages('pictures'); ?>
    </div>
    <div>
        <input class="btn btn-primary" type="submit" value="Modifier l'annonce">
        <a class="btn btn-danger" href="<?= WEB_ROOT . 'mes-annonces'; ?>">Annuler</a>
    </div>
</form>