<?php

use frontend\controllers\AviaController;
use frontend\controllers\RailRoadController;

/**
 * @var $this yii\web\View
 */
$this->title = 'Билетур';
?>
<div class="row">
    <div class="row column-5">
        <div class="col-xs-12 text-center">
            <div class="banner-hr-block">

            </div>
        </div>
    </div>
</div>
<div class="row column-5" style="margin-top: 20px;">
    <div class="col-md-2">
        <a href="<?= AviaController::getActionUrl(AviaController::ACTION_INDEX) ?>" class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Авиабилеты">
            <span>Авиабилеты</span>
        </a>
    </div>
    <div class="col-md-2">
        <a href="<?= RailRoadController::getActionUrl(RailRoadController::ACTION_INDEX) ?>" class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Ж/Д билеты">
            <span>Ж/Д билеты</span>
        </a>
    </div>
    <div class="col-md-2">
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Экскурсии">
            <span>Экскурсии</span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Туры">
            <span>Туры</span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Отели">
            <span>Отели</span>
        </div>
    </div>
</div>

