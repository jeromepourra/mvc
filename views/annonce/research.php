<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
require_once ROOT . 'views/helpers/DateHelper.php';
require_once ROOT . 'views/helpers/NumberHelper.php';
$aAnnonces = FrontHelper::get('Annonces');
?>

<h1 class="mb-5"><?= FrontHelper::get('body-title'); ?></h1>

<?php if (empty($aAnnonces)) : ?>

    <p>Aucune annonce n'a été trouvé pour cette recherche.</p>
    <a class="btn btn-primary align-self-center" href="<?= WEB_ROOT; ?>">Rechercher à nouveau</a>

<?php else : ?>

    <?php foreach ($aAnnonces as $aAnnonce) : ?>

        <?php
        $oAnnonce = FrontHelper::getFrom($aAnnonce, 'Annonce');
        $oPicture = FrontHelper::getFrom($aAnnonce, 'Picture');
        $oCategory = FrontHelper::getFrom($aAnnonce, 'Category');
        $oUser = FrontHelper::getFrom($aAnnonce, 'User');

        $nAnnonceId = FrontHelper::getFrom($oAnnonce, '_id');
        $sAnnonceTitle = FrontHelper::getFrom($oAnnonce, 'title');
        $nAnnoncePrice = FrontHelper::getFrom($oAnnonce, 'price');
        $sAnnonceDescription = FrontHelper::getFrom($oAnnonce, 'description');
        $sAnnonceDate = FrontHelper::getFrom($oAnnonce, 'date');

        $nPictureId = FrontHelper::getFrom($oPicture, '_id');
        $sPictureExt = FrontHelper::getFrom($oPicture, 'extension');

        $sCategoryName = FrontHelper::getFrom($oCategory, 'name');

        $sUserPseudo = FrontHelper::getFrom($oUser, 'pseudo');
        ?>

        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-3">
                    <img class="img-fluid rounded-start" src="<?= WEB_ROOT . Constants::PATH_ANNONCES . str_pad($nAnnonceId, Constants::ANNONCE_PICTURE_DIRECTORY_NAME_LENGTH, "0", STR_PAD_LEFT) . '/' . $nPictureId . '.' . $sPictureExt; ?>" alt="<?= $sAnnonceTitle; ?>">
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?= WEB_ROOT . 'annonce/' . $nAnnonceId; ?>"><?= $sAnnonceTitle; ?></a>
                        </h5>
                        <p class="card-text"><?= (new NumberHelper())->format($nAnnoncePrice); ?> €</p>
                        <p class="card-text"><?= $sAnnonceDescription; ?></p>
                        <p class="card-text">
                            <small class="text-muted">
                                Par <a class="fw-bold" href="<?= WEB_ROOT . 'annonces-utilisateur/' . FrontHelper::getFrom($oUser, '_id') ?>"><?= $sUserPseudo; ?></a> <br>
                                Dans la catégorie <span class="fw-bold"><?= FrontHelper::getFrom($oCategory, 'name'); ?></span> <br>
                                Le <?= (new DateHelper())->getFull($sAnnonceDate); ?>
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <hr>

    <?php endforeach; ?>
<?php endif; ?>