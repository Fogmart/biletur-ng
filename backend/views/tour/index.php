<?php

use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use backend\controllers\TourController;
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                 $this
 * @var \common\forms\tour\SearchForm $form
 */
$this->params['breadcrumbs'][] = 'Туры';
?>

<?php $htmlForm = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

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
<div class="row">
    <div class="col-xs-12">
        <?php if(null !== $form->result):?>
            <table class="table table-striped table-bordered table-condensed">
                <tr>
                    <th width="300px">Изображение для сайта</th>
                    <th>Название</th>
                    <th>#</th>
                </tr>
                <?php foreach ($form->result as $tour):?>
                    <tr>
                        <td  class="text-center"><img src="<?= $tour->getImage() ?>" width="290"></td>
                        <td><strong><?= $tour->title ?></strong></td>
                        <td class="text-center"><a href="<?=TourController::getActionUrl(TourController::ACTION_UPDATE, ['id' => $tour->sourceId])?>"><i class="glyphicon glyphicon-pencil"> </i></a></td>
                    </tr>
                <?php endforeach ?>
            </table>
        <?php endif ?>
    </div>
</div>
