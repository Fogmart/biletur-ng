<?php
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

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
<div class="row">
    <div class="col-xs-4">
		<?= $htmlForm->field($form, $form::ATTR_TOUR_TO)->widget(Select2::class, [
			'data'          => $form->getTourToPaths(),
			'language'      => 'ru',
			'options'       => [
				'placeholder' => 'Страна, город, курорт...',
			],
			'pluginOptions' => [
				'allowClear'         => true,
				'minimumInputLength' => 2,
			],
			'pluginEvents'  => [
				"select2:select" => "function() { $('#w0').submit(); }",
				"select2:unselect" => "function() { $('#w0').submit(); }",

			]
		])->label(false); ?>
    </div>
    <div class="col-xs-3">
    </div>
    <div class="col-xs-3">

    </div>
    <div class="col-xs-2">
		<?= Html::submitButton('Найти', ['class' => 'btn btn-primary', 'id' => 'search-button']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

