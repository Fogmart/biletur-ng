<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                      $this
 * @var \common\forms\excursion\SearchForm $form
 */

use frontend\controllers\ExcursionController;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\JsExpression;

?>
<?php $htmlForm = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

<?= $htmlForm->field($form, $form::ATTR_PAGE)->hiddenInput()->label(false) ?>
<?= $htmlForm->field($form, $form::ATTR_CITY_TAG)->hiddenInput()->label(false) ?>
<?= $htmlForm->field($form, $form::ATTR_CITY_NAME)->hiddenInput()->label(false) ?>
<div class="row">

	<div class="col-xs-4 col-xs-offset-3">
		<?=
		$htmlForm->field($form, $form::ATTR_CITY)->widget(Select2::class, [
			'model'         => $form,
			'attribute'     => $form::ATTR_CITY,
			'data'          => $form->getLastAutocompleteCityTripster(),
			'options'       => [
				'multiple' => false,
				'prompt'   => 'Нет',
				'class'    => 'biletur-text-input'
			],
			'pluginEvents'  => [
				"select2:select" => "function() { $('#w0').submit(); }",
			],
			'pluginOptions' => [
				'placeholder'        => 'Город, страна...',
				'ajax'               => [
					'url'      => ExcursionController::getActionUrl(ExcursionController::ACTION_FIND_BY_NAME),
					'dataType' => 'json',
					'data'     => new JsExpression('function(params) { return {q:params.term, needType:"city"}; }')
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
		<?= Html::submitButton('Найти', ['class' => 'btn btn-primary', 'id' => 'search-button']) ?>
	</div>
</div>

<?php ActiveForm::end(); ?>
