<?php

use frontend\controllers\AviaController;
use kartik\checkbox\CheckboxX;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\select2\Select2;
use yii\web\JsExpression;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\forms\etm\SearchForm $form
 */
?>
<?php $htmlForm = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
	<div class="row">
		<div class="col-xs-3">
			<?=
			$htmlForm->field($form, $form::ATTR_AIRPORT_FROM)->widget(Select2::class, [
				'model'         => $form,
				'attribute'     => $form::ATTR_AIRPORT_FROM,
				'options'       => [
					'multiple' => false,
					'prompt'   => 'Нет',
					'class'    => 'biletur-text-input'
				],
				'pluginOptions' => [
					'placeholder'        => 'Откуда',
					'ajax'               => [
						'url'      => AviaController::getActionUrl(AviaController::ACTION_GET_AIRPORT),
						'dataType' => 'json',
						'data'     => new JsExpression('function(params) { return {q:params.term}; }')
					],
					'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
					'templateSelection'  => new JsExpression('function (data) { return data.text; }'),
					'allowClear'         => false,
					'minimumInputLength' => 3,
				],
			])->label(false);
			?>
		</div>
		<div class="col-xs-3">
			<?=
			$htmlForm->field($form, $form::ATTR_AIRPORT_TO)->widget(Select2::class, [
				'model'         => $form,
				'attribute'     => $form::ATTR_AIRPORT_TO,
				'options'       => [
					'multiple' => false,
					'prompt'   => 'Нет',
					'class'    => 'biletur-text-input'
				],
				'pluginOptions' => [

					'placeholder'        => 'Куда',
					'ajax'               => [
						'url'      => AviaController::getActionUrl(AviaController::ACTION_GET_AIRPORT),
						'dataType' => 'json',
						'data'     => new JsExpression('function(params) { return {q:params.term}; }')
					],
					'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
					'templateSelection'  => new JsExpression('function (data) { return data.text; }'),
					'allowClear'         => false,
					'minimumInputLength' => 3,
				],
			])->label(false);
			?>
		</div>
		<div class="col-xs-3">
			<?= $htmlForm->field($form, $form::ATTR_DATE)->widget(DatePicker::class, [
				'options'       => [
					'placeholder' => 'Туда',
					'class'       => 'biletur-text-input'
				],
				'removeButton'  => false,
				'pluginOptions' => [
					'startDate' => date('Y-m-d'),
					'autoclose' => true,
					'format'    => 'yyyy-mm-dd',
				]
			])->label(false);
			?>
		</div>
		<div class="col-xs-3">
			<?= $htmlForm->field($form, $form::ATTR_BACK_DATE)->widget(DatePicker::class, [
				'options'       => [
					'placeholder' => 'Обратно',
					'class'       => 'biletur-text-input'
				],
				'pluginOptions' => [
					'autoclose' => true
				]
			])->label(false)
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-3">

		</div>
		<div class="col-xs-3">
			<?= $htmlForm->field($form, $form::ATTR_IS_DIRECT)->widget(CheckboxX::class, [
				'autoLabel'     => true,
				'pluginOptions' => [
					'threeState' => false
				],
				'pluginEvents'  => [
					/*'change' => new JsExpression("function() { $('#searchform-page').val(1); $('#w0').submit(); }"),*/
				],
			])->label(false); ?>
		</div>
		<div class="col-xs-3">
			<?= $htmlForm->field($form, $form::ATTR_IS_FIXED_DATE)->widget(CheckboxX::class, [
				'autoLabel'     => true,
				'pluginOptions' => [
					'threeState' => false
				],
				'pluginEvents'  => [
					/*'change' => new JsExpression("function() { $('#searchform-page').val(1); $('#w0').submit(); }"),*/
				],
			])->label(false); ?>
		</div>
		<div class="col-xs-3">
			<?= Html::submitButton('Найти билеты', ['class' => 'btn btn-danger fl-r', 'id' => 'search-button']) ?>
		</div>
	</div>
	<div class="row additional-filters">
		<div class="col-xs-3">
			<?= $htmlForm->field($form, $form::ATTR_CLASS)->dropDownList($form::CLASSES) ?>
		</div>
		<div class="col-xs-3">
			<?= $htmlForm->field($form, $form::ATTR_ADULT_COUNT)->textInput() ?>
		</div>
		<div class="col-xs-3">
			<?= $htmlForm->field($form, $form::ATTR_CHILD_COUNT)->textInput() ?>
		</div>
		<div class="col-xs-3">
			<?= $htmlForm->field($form, $form::ATTR_INFANT_COUNT)->textInput() ?>
		</div>
	</div>
<?php ActiveForm::end(); ?>