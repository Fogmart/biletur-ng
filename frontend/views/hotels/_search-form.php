<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                   $this
 * @var \common\forms\hotels\SearchForm $form
 */

use frontend\controllers\HotelsController;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\JsExpression;

?>

<?php $htmlForm = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
<?= $htmlForm->field($form, $form::ATTR_OBJECT_TYPE)->hiddenInput()->label(false) ?>
<div class="row">
    <div class="col-xs-4">
		<?=
		$htmlForm->field($form, $form::ATTR_TITLE)->widget(Select2::class, [
			'model'         => $form,
			'attribute'     => $form::ATTR_TITLE,
			'data'          => $form->getLastAutocompleteOstrovok(),
			'options'       => [
				'multiple' => false,
				'prompt'   => 'Нет',
			],
			'pluginOptions' => [
				'placeholder'        => 'Регион, отель',
				'ajax'               => [
					'url'      => HotelsController::getActionUrl(HotelsController::ACTION_FIND_BY_NAME),
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
		<?= $htmlForm->field($form, $form::ATTR_CHECK_IN)->widget(DatePicker::class, [
			'options'       => ['placeholder' => 'Заезд'],
			'type'          => DatePicker::TYPE_INPUT,
			'pluginOptions' => [
				'autoclose' => true
			]
		])->label(false);
		?>
    </div>
    <div class="col-xs-2">
		<?= $htmlForm->field($form, $form::ATTR_CHECK_OUT)->widget(DatePicker::class, [
			'options'       => ['placeholder' => 'Выезд'],
			'type'          => DatePicker::TYPE_INPUT,
			'pluginOptions' => [
				'autoclose' => true
			]
		])->label(false);
		?>
    </div>
    <div class="col-xs-1">
		<?= $htmlForm->field($form, $form::ATTR_ADULT_COUNT)->widget(TouchSpin::class, [
			'pluginOptions' => [
				'verticalbuttons' => true,
				'min'             => 1,
				'max'             => 6,
			]
		])->label(false);
		?>
    </div>
    <div class="col-xs-1">
		<?= $htmlForm->field($form, $form::ATTR_CHILD_COUNT)->widget(TouchSpin::class, [
			'pluginOptions' => [
				'verticalbuttons' => true,
				'min'             => 0,
				'max'             => 4,
			]
		])->label(false);
		?>
    </div>
    <div class="col-xs-2">
		<?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

