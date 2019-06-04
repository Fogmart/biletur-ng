<?php
use frontend\controllers\SiteController;
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
?>
<div class="row">
    <div class="col-xs-12">
        <div class="block-panel">
            <a href="<?= SiteController::getActionUrl(SiteController::ACTION_LOGOUT)?>" class="btn btn-primary btn-sm">Выйти</a>
        </div>
    </div>
</div>