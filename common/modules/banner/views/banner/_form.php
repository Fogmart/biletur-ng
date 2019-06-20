<?php

use backend\controllers\UploadFileController;
use common\base\helpers\DateHelper;
use common\modules\banner\models\Banner;
use dosamigos\fileupload\FileUploadUI;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\banner\models\Banner */
/* @var $form yii\widgets\ActiveForm */

/** @var \common\models\ObjectFile $image */
$image = $model->getImage();
?>

<div class="banner-form">
	<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="form-group col-xs-12 col-md-4">
			<?= $form->field($model, $model::ATTR_TITLE)->textInput() ?>
        </div>
        <div class="form-group col-xs-12 col-md-4">
			<?= $form->field($model, $model::ATTR_URL)->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-md-4">
			<?= $form->field($model, $model::ATTR_UTM)->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-xs-12 col-md-4">
			<?php
			echo '<label class="control-label">Период показа</label>';
			echo DatePicker::widget([
				'model'         => $model,
				'attribute'     => $model::ATTR_BEG_DATE,
				'attribute2'    => $model::ATTR_END_DATE,
				'options'       => ['placeholder' => 'Дата начала'],
				'options2'      => ['placeholder' => 'Дата конца'],
				'type'          => DatePicker::TYPE_RANGE,
				'form'          => $form,
				'pluginOptions' => [
					'format'         => 'yyyy-mm-dd',
					'autoclose'      => true,
					'todayHighlight' => true,
					'startDate'      => date(DateHelper::DATE_FORMAT)
				]
			]);
			?>
        </div>
        <div class="form-group col-xs-12 col-md-4">
			<?= $form->field($model, $model::ATTR_ZONE)->widget(Select2::class, [
				'hideSearch'    => false,
				'data'          => Banner::ZONE_NAMES,
				'options'       => ['placeholder' => 'Выбрать...'],
				'pluginOptions' => [
					'allowClear' => true
				],
			])->label('Место размещения');
			?>
        </div>
    </div>
	<?php if (false === $model->isNewRecord): ?>
        <div class="row">
            <div class="form-group col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading bg-primary">
                        Изображение
                    </div>
                    <div class="panel-body" id="files">
						<?php if (null === $image): ?>
							<?= FileUploadUI::widget([
								'model'         => $model,
								'attribute'     => $model::ATTR_FILE,
								'url'           => [
									'/upload-file/index',
									'objectName' => Banner::class,
									'objectId'   => $model->id,
								],
								'gallery'       => false,
								'fieldOptions'  => [
									'accept' => '*'
								],
								'clientOptions' => [
									'maxFileSize' => 2000000,
								],
								'clientEvents'  => [
									'fileuploaddone' => 'function(e, data) { console.log(e); console.log(data); }',
									'fileuploadfail' => 'function(error, data) { console.log(error); console.log(data); }',
								]
								,
							]);
							?>
						<?php endif ?>
						<?php if (null !== $image): ?>
                            <table class="table table-bordered table-condensed table-hover table-small">
                                <thead>
                                <tr>
                                    <td colspan="2"><?= $image->filename ?></td>
                                </tr>
                                </thead>

                                <tr>
                                    <td><img width="200px" src="<?= $image->getWebUrl() ?>"></td>
                                    <td width="300px" class="text-center">
                                        <a class="btn btn-success btn-sm" href="<?= UploadFileController::getActionUrl(UploadFileController::ACTION_GET_FILE, ['id' => $image->id]) ?>">
                                            Скачать
                                        </a>
                                        <a class="btn btn-danger btn-sm" href="<?= UploadFileController::getActionUrl(UploadFileController::ACTION_DELETE, ['id' => $image->id, 'bannerId' => $model->id]) ?>">
                                            Удалить
                                        </a>
                                    </td>
                                </tr>
                            </table>
						<?php endif ?>
                    </div>
                </div>
            </div>
        </div>
	<?php endif ?>
    <br>
    <hr>
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

</div>
<?php ActiveForm::end(); ?>
</div>
