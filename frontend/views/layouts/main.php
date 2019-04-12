<?php

/* @var $this \yii\web\View */

/* @var $content string */

use frontend\assets\AppAsset;
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
    <div class="container">
		<?= $this->render('_header') ?>
        <div class="inner-container text-center">
			<?= $content ?>
        </div>
    </div>
</div>
<?= $this->render('_footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
