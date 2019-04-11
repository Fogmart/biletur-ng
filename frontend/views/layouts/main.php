<?php

/* @var $this \yii\web\View */

/* @var $content string */

use frontend\assets\AppAsset;
use rmrevin\yii\fontawesome\FAS;
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
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <a href="/"><img src="/images/logo.png" class="img-responsive" alt="Всероссийская сеть Билетур" height="55"></a>
            </div>
            <div class="col-md-7 col-sm-12" style="padding-top: 25px;">
                <input type="text" class="biletur-text-input">
                <input type="text" class="biletur-text-input">
                <button class="btn biletur-btn"><?= FAS::icon('user', ['style' => 'margin-right: 5px;']) ?><?= !Yii::$app->user->isGuest ? 'Личный кабинет' : 'Войти' ?></button>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="pull-right text-center call-center-block visible-lg visible-md visible-sm visible-xs" style="display: inline-block;">
                    <a href="http://www.biletur.ru/news/shnews.asp?id=5634">
                        Контактный Центр<br>
                        <span class="glyphicon glyphicon-phone-alt"></span> <strong>8-800-200-66-66</strong><br>
                        звонки по России – бесплатно<br>
                    </a>
                </div>
            </div>
        </div>
        <div class="row hr-del-line">
            <div class="col-xs-12"></div>
        </div>
        <div class="inner-container text-center">
			<?= $content ?>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
