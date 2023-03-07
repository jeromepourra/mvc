<?php
require_once ROOT . 'views/helpers/FrontHelper.php';
require_once ROOT . 'views/helpers/DateHelper.php';
require_once ROOT . 'views/helpers/NumberHelper.php';
?>

<?php
$oAnnonce = FrontHelper::get('Annonce');
$aPictures = FrontHelper::get('Pictures');
$oCategory = FrontHelper::get('Category');
$oUser = FrontHelper::get('User');
?>

<div class="card mb-3 p-1">
    <div class="row g-0">
        <div class="col-md-4">
            <div id="carousel" class="carousel slide">
                <div class="carousel-inner">
                    <?php $bClassActive = true; ?>
                    <?php foreach ($aPictures as $oPicture) : ?>
                        <div class="carousel-item <?= $bClassActive ? 'active' : ''; ?>">
                            <img class="img-fluid rounded" src="<?= WEB_ROOT . Constants::PATH_ANNONCES . str_pad($oAnnonce->_id, Constants::ANNONCE_PICTURE_DIRECTORY_NAME_LENGTH, "0", STR_PAD_LEFT) . '/' . $oPicture->_id . '.' . $oPicture->extension; ?>" alt="<?= $oAnnonce->title; ?>">
                        </div>
                        <?php $bClassActive = false; ?>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card-body">

                <h5 class="card-title">
                    <?= $oAnnonce->title; ?>
                </h5>

                <p class="card-text">
                    <?= (new NumberHelper())->format($oAnnonce->price); ?> €
                </p>
                <p class="card-text">
                    <?= $oAnnonce->description; ?>
                </p>
                <p class="card-text">
                    <small class="d-block">
                        Dans la catégorie :
                        <span class="fw-bold">
                            <?= $oCategory->name; ?>
                        </span>
                    </small>
                    <small class="d-block">Le <?= (new DateHelper())->getFull($oAnnonce->date); ?></small>
                </p>

                <p class="card-text">
                    Contacter : <?= $oUser->pseudo ?>
                    <small class="d-block">
                        Tel:
                        <a class="fw-bold" href="<?= 'tel:+33' . substr($oUser->phone, 1) ?>">
                            <?= $oUser->phone; ?>
                        </a>
                    </small>
                    <small class="d-block">
                        Email:
                        <a class="fw-bold" href="<?= 'mailto:' . $oUser->email ?>">
                            <?= $oUser->email ?>
                        </a>
                    </small>
                    <a class="btn btn-primary" href="<?= WEB_ROOT . 'envoyer-message/' . $oUser->_id ?>">Envoyer un message</a>
                </p>

            </div>
        </div>
    </div>

</div>