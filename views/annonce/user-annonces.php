<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
require_once ROOT . 'views/helpers/DateHelper.php';
require_once ROOT . 'views/helpers/NumberHelper.php';
$bOwner = FrontHelper::get('owner');
$oUser = FrontHelper::get('User');
$aAnnonces = FrontHelper::get('Annonces');
?>

<?php if (empty($aAnnonces)) : ?>

    <?php if ($bOwner) : ?>
        <h1 class="mb-5">Vous n'avez pas encore posté d'annonces</h1>
        <a class="btn btn-primary align-self-center" href="<?= WEB_ROOT . 'deposer-une-annonce'; ?>">Déposer une annonce</a>
    <?php else : ?>
        <h1 class="mb-5"><?= $oUser->pseudo ?> n'a pas encore posté d'annonces</h1>
    <?php endif; ?>

<?php else : ?>

    <?php if ($bOwner) : ?>
        <div class="mb-5 d-flex justify-content-between">
            <h1 class="m-0">Vos annonces</h1>
            <a class="btn btn-primary align-self-center" href="<?= WEB_ROOT . 'deposer-une-annonce'; ?>">Déposer une annonce</a>
        </div>
    <?php else : ?>
        <h1 class="mb-5">Annonces de <?= $oUser->pseudo ?></h1>
    <?php endif; ?>

    <?php foreach ($aAnnonces as $nIndex => $aAnnonce) : ?>

        <?php
        $oAnnonce = FrontHelper::getFrom($aAnnonce, 'Annonce');
        $aPictures = FrontHelper::getFrom($aAnnonce, 'Picture');
        $oCategory = FrontHelper::getFrom($aAnnonce, 'Category');
        $oUser = FrontHelper::getFrom($aAnnonce, 'User');

        $nAnnonceId = FrontHelper::getFrom($oAnnonce, '_id');
        $sAnnonceTitle = FrontHelper::getFrom($oAnnonce, 'title');
        $nAnnoncePrice = FrontHelper::getFrom($oAnnonce, 'price');
        $sAnnonceDescription = FrontHelper::getFrom($oAnnonce, 'description');
        $sAnnonceDate = FrontHelper::getFrom($oAnnonce, 'date');
        $nAnnonceState = FrontHelper::getFrom($oAnnonce, 'state');
        $sAnnonceStateName = isset(Constants::ANNONCE_STATES[$nAnnonceState]) ? Constants::ANNONCE_STATES[$nAnnonceState] : Constants::ANNONCE_STATES[-1];

        $sCategoryName = FrontHelper::getFrom($oCategory, 'name');
        ?>

        <div class="card mb-3 p-1">
            <div class="row g-0">
                <div class="col-md-3">
                    <div id="carousel<?= $nIndex; ?>" class="carousel slide">
                        <div class="carousel-inner">
                            <?php $bClassActive = true; ?>
                            <?php foreach ($aPictures as $oPicture) : ?>
                                <?php
                                $nPictureId = FrontHelper::getFrom($oPicture, '_id');
                                $sPictureExt = FrontHelper::getFrom($oPicture, 'extension');
                                ?>
                                <div class="carousel-item <?= $bClassActive ? 'active' : ''; ?>">
                                    <img class="img-fluid rounded" src="<?= WEB_ROOT . Constants::PATH_ANNONCES . str_pad($oAnnonce->_id, Constants::ANNONCE_PICTURE_DIRECTORY_NAME_LENGTH, "0", STR_PAD_LEFT) . '/' . $oPicture->_id . '.' . $oPicture->extension; ?>" alt="<?= $oAnnonce->title; ?>">
                                </div>
                                <?php $bClassActive = false; ?>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $nIndex; ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $nIndex; ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?= WEB_ROOT . 'annonce/' . $oAnnonce->_id; ?>"><?= $oAnnonce->title; ?></a>
                        </h5>
                        <p class="card-text"><?= (new NumberHelper())->format($oAnnonce->price); ?> €</p>
                        <p class="card-text"><?= $oAnnonce->description; ?></p>
                        <p class="card-text">
                            <?php if ($bOwner) : ?>
                                <?php
                                // $sStateClass = "";
                                // switch ($oAnnonce->state) {
                                //     case Constants::ANNONCE_STATE_WAIT:
                                //         break;
                                //     case Constants::ANNONCE_STATE_:
                                //         break;
                                //     case Constants::ANNONCE_STATE_WAIT:
                                //         break;
                                //     case Constants::ANNONCE_STATE_WAIT:
                                //         break;
                                // }

                                ?>
                                <small class="d-block"><?= $sAnnonceStateName ?></small>
                            <?php endif; ?>
                            Dans la catégorie <span class="fw-bold"><?= FrontHelper::getFrom($oCategory, 'name'); ?></span> <br>
                            Le <?= (new DateHelper())->getFull($sAnnonceDate); ?>
                        </p>
                    </div>
                </div>
            </div>

            <?php if ($bOwner) : ?>
                <hr>
                <div class="d-flex justify-content-center mb-3">
                    <a class="btn btn-secondary me-3" href="<?= WEB_ROOT . 'modifier-annonce/' . $nAnnonceId; ?>">Modifier l'annonce</a>
                    <a class="btn btn-primary" href="<?= WEB_ROOT . 'retirer-annonce/' . $nAnnonceId; ?>" onclick="return confirm('Etes vous sûr de vouloir effectuer cette action ?')">Retirer l'annonce</a>
                </div>
            <?php endif; ?>

        </div>

        <hr>

    <?php endforeach; ?>
<?php endif; ?>