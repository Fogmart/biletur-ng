<?php
use frontend\controllers\SiteController;
?>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <ul class="list-unstyled">
                    <li><a href="/about/">О компании</a></li>
                    <li><a href="#">Корпоративное обслуживание</a></li>
                    <li><a href="#">Партнерам</a></li>
                    <li><a href="#">Новости</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <ul class="list-unstyled">
                    <li><a href="#">Авиабилеты</a></li>
                    <li><a href="#">Ж/Д билеты</a></li>
                    <li><a href="#">Туры</a></li>
                    <li><a href="#">Круизы</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <ul class="list-unstyled">
                    <li><a href="#">Визы</a></li>
                    <li><a href="#">Страхование</a></li>
                    <li><a href="#">Отели</a></li>
                    <li><a href="#">Экскурсии</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <ul class="list-unstyled">
                    <li><a href="#">Бронирование авто</a></li>
                    <li><a href="#">Билеты на мероприятия</a></li>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <li><a href="<?= SiteController::getActionUrl(SiteController::ACTION_REGISTER) ?>">Регистрация</a></li>
                        <li><a href="<?= SiteController::getActionUrl(SiteController::ACTION_LOGIN) ?>">Вход</a></li>
                    <?php else: ?>
                        <li><a href="<?= SiteController::getActionUrl(SiteController::ACTION_LOGOUT) ?>">Выход</a></li>
                        <li><a href="/internal/">ДСП</a></li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
        <div class="row hr-del-line inverse">
            <div class="col-xs-12"></div>
        </div>
        <div class="row">
	        <div class="col-md-5">
		        © 1998-<?= date('Y')?> АО "Приморское агентство авиационных компаний"
	        </div>
            <div class="col-md-4">

            </div>
            <div class="col-md-3">

            </div>
        </div>
    </div>
</footer>