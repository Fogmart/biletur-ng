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
    <META HTTP-EQUIV="Access-Control-Allow-Origin" CONTENT="http://www.biletur.ru">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<?= $this->render('_counters') ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap" style="padding-left: 15px; padding-right: 15px;" >
        <div class="inner-container">
			<?= $content ?>
        </div>
    </div>
</div>
<?= $this->registerJs('$(this).commonPlugin();')?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
