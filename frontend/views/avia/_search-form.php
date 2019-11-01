<?php

use common\base\helpers\StringHelper;
use common\models\oracle\scheme\arr\ARRAirport;
use frontend\controllers\AviaController;
use kartik\checkbox\CheckboxX;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;
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
				'data'          => [$form->airportFrom => StringHelper::ucfirst(ARRAirport::getNameByIataCode($form->airportFrom))],
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
					'templateResult'     => new JsExpression('function(data) { return data.text; }'),
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
				'data'          => [$form->airportTo => StringHelper::ucfirst(ARRAirport::getNameByIataCode($form->airportTo))],
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
					'startDate' => date('Y-m-d'),
					'autoclose' => true,
					'format'    => 'yyyy-mm-dd',
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
		<div class="col-xs-2">
			<?= $htmlForm->field($form, $form::ATTR_CLASS)->dropDownList($form::CLASSES, ['class' => 'biletur-text-input']) ?>
		</div>
		<div class="col-xs-2">
			<?= $htmlForm->field($form, $form::ATTR_ADULT_COUNT)->widget(TouchSpin::class, [
				'options'       => ['class' => 'biletur-text-input'],
				'pluginOptions' => [
					'verticalbuttons' => true,
					'min'             => 1,
					'max'             => 10,
				]
			]);
			?>
		</div>
		<div class="col-xs-2">
			<?= $htmlForm->field($form, $form::ATTR_CHILD_COUNT)->widget(TouchSpin::class, [
				'options'       => ['class' => 'biletur-text-input'],
				'pluginOptions' => [
					'verticalbuttons' => true,
					'min'             => 0,
					'max'             => 10,
				]
			]);
			?>
		</div>
		<div class="col-xs-2">
			<?= $htmlForm->field($form, $form::ATTR_INFANT_COUNT)->widget(TouchSpin::class, [
				'options'       => ['class' => 'biletur-text-input'],
				'pluginOptions' => [
					'verticalbuttons' => true,
					'min'             => 0,
					'max'             => 10,
				]
			]);
			?>
		</div>
	</div>
<?php ActiveForm::end(); ?>