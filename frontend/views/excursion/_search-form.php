<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                      $this
 * @var \common\forms\excursion\SearchForm $form
 */

use antkaz\vue\Vue;
use frontend\controllers\ExcursionController;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\JsExpression;

?>
<?php Vue::begin([
	'clientOptions' => [
		'data'    => [
			'childCount' => 0
		],
		'watch'   => [
			'childCount' => new JsExpression("function() {console.log('watch')}")
		]
		,
		'methods' => [
			'addChildAge'    => new JsExpression("function() {console.log('add child age')}"),
			'removeChildAge' => new JsExpression("function() {console.log('add child age')}")
		]
	]
]) ?>
<?php $htmlForm = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
    <div class="row">
        <div class="col-xs-6 col-xs-offset-2">
			<?=
			$htmlForm->field($form, $form::ATTR_TITLE)->widget(Select2::class, [
				'model'         => $form,
				'attribute'     => $form::ATTR_TITLE,
				'data'          => $form->getLastAutocompleteTripster(),
				'options'       => [
					'multiple' => false,
					'prompt'   => 'Нет',
				],
				'pluginOptions' => [
					'placeholder'        => 'Страна, город, экскурсия...',
					'ajax'               => [
						'url'      => ExcursionController::getActionUrl(ExcursionController::ACTION_FIND_BY_NAME),
						'dataType' => 'json',
						'data'     => new JsExpression('function(params) { return {q:params.term}; }')
					],
					'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
					'templateResult'     => new JsExpression(' function (data) {
                     if (data.type == "devider") { 
                        return data.text; 
                     }
                     
                     return data.text;
                }'),
					'templateSelection'  => new JsExpression('function (data) { return data.text; }'),
					'allowClear'         => true,
					'minimumInputLength' => 3,
				],
			])->label(false);
			?>
        </div>
        <div class="col-xs-2">
			<?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<?php Vue::end() ?>