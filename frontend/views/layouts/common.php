<?php

/* @var $this \yii\web\View */

/* @var $content string */

use frontend\assets\AppAsset;
use frontend\controllers\AviaController;
use frontend\controllers\ExcursionController;
use frontend\controllers\HotelsController;
use frontend\controllers\TourController;
use yii\helpers\Html;

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
				<li><a href="<?= AviaController::getActionUrl(AviaController::ACTION_INDEX) ?>"><img style="margin-top: -3px; margin-right: 5px;" width="22" src="/images/menu/avia.svg"> Авиабилеты</a></li>
				<li><a href="<?= TourController::getActionUrl(TourController::ACTION_INDEX) ?>"><img style="margin-top: -3px;  margin-right: 5px;" width="22" src="/images/menu/tours.svg"> Туры</a></li>
				<li><a href="<?= TourController::getActionUrl(TourController::ACTION_INDEX) ?>"><img style="margin-top: -3px;  margin-right: 5px;" width="22" src="/images/menu/cruize.svg"> Круизы</a></li>
				<li><a href="<?= HotelsController::getActionUrl(HotelsController::ACTION_INDEX) ?>"><img style="margin-top: -3px;  margin-right: 5px;" width="22" src="/images/menu/hotels.svg"> Отели</a></li>
				<li><a href="<?= ExcursionController::getActionUrl(ExcursionController::ACTION_INDEX) ?>"><img style="margin-top: -3px;  margin-right: 5px;" width="22" src="/images/menu/excursion.svg"> Экскурсии</a></li>
				<li><a href="<?= ExcursionController::getActionUrl(ExcursionController::ACTION_INDEX) ?>"><img style="margin-top: -3px;  margin-right: 5px;" width="22" src="/images/menu/visa.svg"> Визы</a></li>
			</ul>
			<ul class="dop-menu">
				<li><a href="/test-page-2/">Корпоративное обслуживание</a></li>
				<li>Обратная связь</li>
				<li>Новости</li>
				<li>Личный кабинет</li>
				<li>Вакансии</li>
			</ul>
		</div>
		<div style="margin-left: 20px; margin-bottom:10px;">
			<?= $this->render('_header') ?>
		</div>
		<div class="inner-container">
			<?= $content ?>
		</div>
	</div>
</div>
<a id="scrollUp" href="javascript:;" style="display: none;"><span class="glyphicon glyphicon-chevron-up"></span></a>
<?= $this->render('_footer') ?>
<?= $this->registerJs('$(this).commonPlugin();') ?>
<?= $this->registerJs('$(this).layoutPlugin();') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
