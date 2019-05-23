<?php

/* @var $this \yii\web\View */

/* @var $content string */

use frontend\assets\AppAsset;
use frontend\controllers\AviaController;
use frontend\controllers\RailRoadController;
use frontend\controllers\TourController;
use frontend\controllers\HotelsController;
use yii\helpers\Html;
use frontend\controllers\ExcursionController;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<?= $this->render('_counters') ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <div class="container container-with-menu">
        <div class="left-menu">
            <ul class="main-menu">
                <li><a href="<?= AviaController::getActionUrl(AviaController::ACTION_INDEX) ?>">Авиабилеты</a></li>
                <li><a href="<?= RailRoadController::getActionUrl(RailRoadController::ACTION_INDEX) ?>">Ж/Д Билеты</a></li>
                <li><a href="<?= TourController::getActionUrl(TourController::ACTION_INDEX) ?>">Туры</a></li>
                <li><a href="<?= HotelsController::getActionUrl(HotelsController::ACTION_INDEX) ?>">Отели</a></li>
                <li><a href="<?= ExcursionController::getActionUrl(ExcursionController::ACTION_INDEX) ?>">Экскурсии</a></li>
                <li>Круизы</li>
                <li>Визы</li>
                <li>Страхование</li>
            </ul>
            <ul class="dop-menu">
                <li><a href="/test-page-2/">Корпоративное обслуживание</a></li>
                <li>Обратная связь</li>
                <li>Новости</li>
                <li>Личный кабинет</li>
                <li>Вакансии</li>
            </ul>
        </div>
        <div style="margin-left: 220px">
			<?= $this->render('_header') ?>
        </div>
        <div class="inner-container">
			<?= $content ?>
        </div>
    </div>
</div>
<?= $this->render('_footer') ?>
<?= $this->registerJs('$(this).commonPlugin();')?>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
