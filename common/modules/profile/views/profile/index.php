<?php

use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\modules\profile\models\Profile $model
 */
?>
<div class="row">
    <div class="col-xs-12">
        <div class="block-panel">
            <div class="loading-widget" style="display: none;"></div>
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
                    <h3>Личный кабинет</h3>
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
                <div class="col-xs-3">
					<?= $htmlForm->field($model, $model::ATTR_DOB)->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3">
					<?= $htmlForm->field($model, $model::ATTR_EMAIL)->textInput() ?>
                </div>
                <div class="col-xs-3">
					<?= $htmlForm->field($model, $model::ATTR_PHONE)->textInput() ?>
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
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="block-panel">
            <div class="row">
                <div class="col-xs-12">
                    <h3>Заказы</h3>
                </div>
            </div>
        </div>
    </div>
</div>