<?php

use frontend\controllers\AviaController;
use frontend\controllers\RailRoadController;

/**
 * @var $this yii\web\View
 */
$this->title = 'Билетур';
?>
<div class="row">
    <div class="col-xs-12 text-center">
        <div class="banner-hr-block">

        </div>
    </div>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-xs-12 visible-md visible-lg">
        <a href="<?= AviaController::getActionUrl(AviaController::ACTION_INDEX) ?>" class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Авиабилеты">
            <span>Авиабилеты</span>
        </a>
        <a href="<?= RailRoadController::getActionUrl(RailRoadController::ACTION_INDEX) ?>" class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Ж/Д билеты">
            <span>Ж/Д билеты</span>
        </a>
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Экскурсии">
            <span>Экскурсии</span>
        </div>
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Туры">
            <span>Туры</span>
        </div>
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Отели">
            <span>Отели</span>
        </div>
    </div>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-xs-12 visible-md visible-lg">
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Визы">
            <span>Визы</span>
        </div>
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Круизы">
            <span>Круизы</span>
        </div>
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Страхование">
            <span>Страхование</span>
        </div>
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Аренда авто">
            <span>Аренда авто</span>
        </div>
        <div class="text-center index-icons-block">
            <img src="/images/plane.png" alt="Билеты на мероприятия">
            <span>Билеты на мероприятия</span>
        </div>
    </div>
</div>
