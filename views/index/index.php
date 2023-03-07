<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
?>

<form action="<?= WEB_ROOT . "rechercher" ?>" method="get">

    <div class="mb-3">
        <label class="form-label" for="category">Trier par catégorie</label>
        <select class="form-select" name="category" id="category">
            <option value="" selected>Toutes catégories</option>
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
        <label class="form-label" for="keyword">Recherche par mot clef</label>
        <input class="form-control" name="keyword" id="keyword" type="search">
    </div>

    <div class="mb-3">
        <label class="form-label" for="pricemin">Trier par prix</label>
        <div class="d-flex">
            <div class="input-group me-1">
                <label class="input-group-text" for="pricemin">Min €</label>
                <input class="form-control" name="pricemin" id="pricemin" type="number" step="0.01" min="0">
                <label class="input-group-text" for="pricemax">Max €</label>
                <input class="form-control" name="pricemax" id="pricemax" type="number" step="0.01" min="0">
            </div>
        </div>
    </div>

    <div>
        <input class="btn btn-primary" type="submit" value="Rechercher">
    </div>

</form>