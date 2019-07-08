<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                   $this
 * @var \common\forms\hotels\SearchForm $form
 */

use antkaz\vue\Vue;
use common\base\helpers\DateHelper;
use frontend\controllers\HotelsController;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\touchspin\TouchSpin;
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
					'placeholder'        => 'Город, регион, отель',
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
		<div class="col-xs-4">
			<?php echo DatePicker::widget([
				'model'         => $form,
				'separator'     => '<img width="23" src="/images/favicon.ico">',
				'attribute'     => $form::ATTR_CHECK_IN,
				'attribute2'    => $form::ATTR_CHECK_OUT,
				'options'       => ['placeholder' => 'Дата заеда', 'class' => 'biletur-text-input br-l8'],
				'options2'      => ['placeholder' => 'Дата выезда', 'class' => 'biletur-text-input br-r8'],
				'type'          => DatePicker::TYPE_RANGE,
				'form'          => $htmlForm,
				'pluginOptions' => [
					'format'         => 'yyyy-mm-dd',
					'autoclose'      => true,
					'todayHighlight' => true,
					'startDate'      => date(DateHelper::DATE_FORMAT)
				]
			]);
			?>
		</div>
		<div class="col-xs-1">
			<?= $htmlForm->field($form, $form::ATTR_ADULT_COUNT)->widget(TouchSpin::class, [
				'options'       => ['class' => 'biletur-text-input'],
				'pluginOptions' => [
					'verticalbuttons' => true,
					'min'             => 1,
					'max'             => 6,
				]
			])->label(false);
			?>
		</div>
		<div class="col-xs-1">
			<?= $htmlForm->field($form, $form::ATTR_CHILD_COUNT)->textInput()
				->widget(TouchSpin::class, [
					'options'       => [
						'v-model' => 'childCount',
						'class'   => 'biletur-text-input',
					],
					'pluginOptions' => [
						'verticalbuttons' => true,
						'min'             => 0,
						'max'             => 4,
					],
					'pluginEvents'  => [
						"touchspin.on.startspin " => new JsExpression("function() {
							if ($(this).val() > 0) {
								
							}
						}")
					]
				])->label(false);
			?>
		</div>
		<div class="col-xs-2">
			<?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
		</div>
	</div>
<?php if (count($form->filters)): ?>
	<div class="row">
		<div class="col-xs-12">
			<?php
			\common\base\helpers\Dump::d($form->filters)
			?>
		</div>
	</div>
<?php endif ?>
<?php ActiveForm::end(); ?>

<?php Vue::end() ?>