<?php

use frontend\controllers\SiteController;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\modules\profile\models\Profile $model
 */
?>
<div class="block-panel">
	<?php Pjax::begin(); ?>
	<?php $htmlForm = ActiveForm::begin(
		[
			'options' =>
				[
					'data-pjax' => true
				]
		]
	); ?>
    <div class="row">
        <div class="col-xs-12">
            <a href="<?= SiteController::getActionUrl(SiteController::ACTION_LOGOUT) ?>" class="pull-right btn btn-warning btn-sm">Выйти</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3">
			<?= $htmlForm->field($model, $model::ATTR_L_NAME)->textInput() ?>
        </div>
        <div class="col-xs-3">
			<?= $htmlForm->field($model, $model::ATTR_F_NAME)->textInput() ?>
        </div>
        <div class="col-xs-3">
			<?= $htmlForm->field($model, $model::ATTR_S_NAME)->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-primary">Сохранить</button>
        </div>
    </div>
	<?php ActiveForm::end(); ?>
	<?php Pjax::end(); ?>
</div>
