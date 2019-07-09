<?php

use kartik\select2\Select2;
use kartik\slider\Slider;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\JsExpression;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                 $this
 * @var \common\forms\tour\SearchForm $form
 *
 */
?>

<?php $htmlForm = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
<?= $htmlForm->field($form, $form::ATTR_CITY_IN_WAY_POINT)->hiddenInput()->label(false) ?>
<?= $htmlForm->field($form, $form::ATTR_COUNT)->hiddenInput()->label(false) ?>
<div class="row">
	<div class="col-xs-4">
		<?= $htmlForm->field($form, $form::ATTR_TOUR_TO)->widget(Select2::class, [
			'data'          => $form->getTourToPaths(),
			'language'      => 'ru',
			'options'       => [
				'placeholder' => 'Страна, город, курорт...',
				'class'       => 'biletur-text-input'
			],
			'pluginOptions' => [
				'allowClear'         => true,
				'minimumInputLength' => 2,
			],
			'pluginEvents'  => [
				"select2:select"   => "function() { $('#w0').submit(); }",
				"select2:unselect" => "function() { $('#w0').submit(); }",
			]
		])->label(false); ?>
	</div>
	<div class="col-xs-3">
		<?= $htmlForm->field($form, $form::ATTR_PRICE_RANGE)->widget(Slider::class, [
			'sliderColor'   => Slider::TYPE_GREY,
			'pluginOptions' => [
				'min'       => $form->priceMinMax[0],
				'max'       => $form->priceMinMax[1],
				'step'      => 3000,
				'range'     => true,
				'tooltip'   => 'always',
				'formatter' => new yii\web\JsExpression("function(val) {
						var priceMin = new Number(val[0]);
						var priceMax = new Number(val[1]);
						return 'Цена: ' + priceMin.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ') + ' - ' + priceMax.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ') + ' руб';
			        }")
			],
			'pluginEvents'  => [
				'slideStop' => new JsExpression("function() { $('#w0').submit(); }")
			],
		])->label(false);
		?>
	</div>
	<div class="col-xs-3">

	</div>
	<div class="col-xs-2">
		<?= Html::submitButton('Найти', ['class' => 'btn btn-primary', 'id' => 'search-button', 'style' => 'display: none;']) ?>
	</div>
</div>
<?php ActiveForm::end(); ?>

