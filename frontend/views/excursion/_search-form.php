<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                      $this
 * @var \common\forms\excursion\SearchForm $form
 */

use frontend\controllers\ExcursionController;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;
use kartik\slider\Slider;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\JsExpression;

?>
<?php $htmlForm = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
<?= $htmlForm->field($form, $form::ATTR_PAGE)->hiddenInput()->label(false) ?>
<?= $htmlForm->field($form, $form::ATTR_CITY_TAG)->hiddenInput()->label(false) ?>
<?= $htmlForm->field($form, $form::ATTR_CITY_NAME)->hiddenInput()->label(false) ?>
<?= $htmlForm->field($form, $form::ATTR_SORT_TYPE)->hiddenInput()->label(false) ?>
<div class="row">
	<div class="col-xs-4">
		<span class="lbl-sm-blue">Направление</span>
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
				"select2:select" => new JsExpression("function() { $('#searchform-page').val(1); $('#searchform-citytag').val(''); $('#w0').submit();}")
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
	<div class="col-xs-4">
		<?= $htmlForm->field($form, $form::ATTR_IS_CHILD_FRIENDLY)->widget(CheckboxX::class, [
			'autoLabel'     => true,
			'pluginOptions' => [
				'threeState' => false
			],
			'pluginEvents'  => [
				'change' => new JsExpression("function() { $('#searchform-page').val(1); $('#w0').submit(); }"),
			],
		])->label(false); ?>
	</div>
	<div class="col-xs-2">
		<?= Html::submitButton('Найти', ['class' => 'btn btn-primary', 'id' => 'search-button', 'style' => 'display: none;']) ?>
	</div>
</div>
<?php if (!empty($form->city) || !empty($form->cityName)): ?>
	<div class="row">
		<div class="col-xs-11 col-xs-offset-1">
			<span class="popup-filter-h">Фильтры</span> <a class="popup-filter" href="javascript:;"><i class="glyphicon glyphicon-tag"></i> Цена <i class="glyphicon glyphicon-chevron-down"></i>
				<span>
					<?= $htmlForm->field($form, $form::ATTR_PRICE_RANGE, ['template' => "{input}"])->widget(Slider::class, [
						'sliderColor'   => Slider::TYPE_GREY,
						'pluginOptions' => [
							'min'       => $form->priceMinMax[0],
							'max'       => $form->priceMinMax[1],
							'step'      => 50,
							'range'     => true,
							'tooltip'   => 'hide',
							'formatter' => new yii\web\JsExpression("function(val) {
							var priceMin = new Number(val[0]);
							var priceMax = new Number(val[1]);
							
							return 'Цена: ' + priceMin.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ') + ' - ' + priceMax.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ') + ' руб';
						}")
						],
						'pluginEvents'  => [
							'slideStop' => new JsExpression("function() { $('#searchform-page').val(1); $('#w0').submit(); }"),
							'slide'     => new JsExpression("function(el) {
									let priceRange = $('#searchform-pricerange').val().split(',');
									$('#price-min').val(priceRange[0] + ' руб.');
									$('#price-max').val(priceRange[1] + ' руб.');
							 }"),
						],
					])->label(false);
					?>

					<input disabled type="text" class="price-slider-input" id="price-min" value="<?= $form->priceMinMax[0] ?>">
					<input disabled type="text" class="price-slider-input" id="price-max" value="<?= $form->priceMinMax[1] ?>">
				</span>
			</a>
			<a class="popup-filter" href="javascript:;"><i class="glyphicon glyphicon-time"></i> Длительность <i class="glyphicon glyphicon-chevron-down"></i>
				<span>
					<?= $htmlForm->field($form, $form::ATTR_TIME_RANGE, ['template' => "{input}"])->widget(Slider::class, [
						'sliderColor'   => Slider::TYPE_GREY,
						'pluginOptions' => [
							'min'       => $form->timeMinMax[0],
							'max'       => $form->timeMinMax[1],
							'step'      => 1,
							'range'     => true,
							'tooltip'   => 'hide',
							'formatter' => new yii\web\JsExpression("function(val) {
							var priceMin = new Number(val[0]);
							var priceMax = new Number(val[1]);
							
							return priceMin.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ') + ' - ' + priceMax.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ') + ' ч.';
						}")
						],
						'pluginEvents'  => [
							'slideStop' => new JsExpression("function() { $('#searchform-page').val(1); $('#w0').submit(); }"),
							'slide'     => new JsExpression("function(el) {
									let timeRange = $('#searchform-timerange').val().split(',');
									$('#time-min').val(timeRange[0] + ' ч.');
									$('#time-max').val(timeRange[1] + ' ч.');
							 }"),
						],
					])->label(false);
					?>

					<input disabled type="text" class="time-slider-input" id="time-min" value="<?= $form->timeMinMax[0] ?>">
					<input disabled type="text" class="time-slider-input" id="time-max" value="<?= $form->timeMinMax[1] ?>">
				</span>
			</a>
			<a class="popup-filter" href="javascript:;"><?= $form::SORT_NAMES[$form->sortType] ?> <i class="glyphicon glyphicon-chevron-down"></i>
				<span>
					<div href="javascript:;" class="sort-type" data-id="<?= $form::SORT_POPULARITY ?>"><?= $form::SORT_NAMES[$form::SORT_POPULARITY] ?></div>
					<div href="javascript:;" class="sort-type" data-id="<?= $form::SORT_PRICE ?>"><?= $form::SORT_NAMES[$form::SORT_PRICE] ?></div>
					<!--<div href="javascript:;" class="sort-type" data-id="<?= $form::SORT_REVIEW_COUNT ?>"><?= $form::SORT_NAMES[$form::SORT_REVIEW_COUNT] ?></div>-->
				</span>
			</a>
		</div>
	</div>
<?php endif ?>

<?php if (count($form->tags) > 0): ?>
	<div class="row additional-filters">
		<div class="col-xs-12 tags">
			<hr>
			<?php
			$active = '';
			if (empty($form->cityTag)) {
				$active = ' active';
			}
			?>
			<a href="#" class="tag <?= $active ?>" data-id="">Все экскурсии</a>
			<?php foreach ($form->tags as $tag): ?>
				<?php
				$active = '';
				if ($form->cityTag == $tag->id) {
					$active = ' active';
				}
				?>
				<a href="#" class="tag <?= $active ?>" data-id="<?= $tag->id ?>"><?= $tag->name ?> [<?= $tag->experience_count ?>]</a>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif ?>

<?php ActiveForm::end(); ?>
