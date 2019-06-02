<?php

use rmrevin\yii\fontawesome\FAS;
use frontend\controllers\ProfileController;
use frontend\controllers\SiteController;

?>
<div class="row">
    <div class="col-md-3 col-sm-12">
        <a href="/"><img src="/images/logo_500.png" class="img-responsive" alt="Всероссийская сеть Билетур" height="55"></a>
    </div>
    <div class="col-md-7 col-sm-12" style="padding-top: 25px;">
        <input type="text" class="biletur-text-input">
        <input type="text" class="biletur-text-input">
        <a href="<?= (Yii::$app->user->isGuest ? SiteController::getActionUrl(SiteController::ACTION_LOGIN) : ProfileController::getActionUrl(ProfileController::ACTION_INDEX)) ?>" class="btn biletur-btn"><?= FAS::icon('user', ['style' => 'margin-right: 5px;']) ?><?= !Yii::$app->user->isGuest ? 'Личный кабинет' : 'Войти' ?></a>
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