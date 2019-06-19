<?php

use backend\controllers\UploadFileController;
use common\modules\banner\models\Banner;
use dosamigos\fileupload\FileUploadUI;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\banner\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">
	<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-md-4">
			<?= $form->field($model, $model::ATTR_TITLE)->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-4">
			<?= $form->field($model, $model::ATTR_URL)->textInput() ?>
        </div>
        <div class="col-xs-12 col-md-4">
			<?= $form->field($model, $model::ATTR_UTM)->textInput() ?>
        </div>
    </div>
</div>
<?php if (false === $model->isNewRecord): ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading bg-primary">
                    Изображения <span class="label label-success"><?= count($model->image) ?></span>
                </div>
                <div class="panel-body" id="files">
					<?= FileUploadUI::widget([
						'model'         => $model,
						'attribute'     => $model::ATTR_FILE,
						'url'           => [
							'upload-file/index',
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
					<?php if (count($model->image) > 0): ?>
                        <table class="table table-bordered table-condensed table-hover table-small">
                            <thead>
                            <tr>
                                <td colspan="2">Изображение</td>
                            </tr>
                            </thead>

                            <tr>
                                <td><img width="200px" src="<?= $model->image->getWebUrl() ?>"></td>
                                <td width="300px" class="text-center">
                                    <a class="btn btn-success btn-sm" href="<?= UploadFileController::getActionUrl(UploadFileController::ACTION_GET_FILE, ['id' => $model->image->id]) ?>">
                                        Скачать
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="<?= UploadFileController::getActionUrl(UploadFileController::ACTION_DELETE, ['id' => $model->image->id, 'bannerId' => $model->id]) ?>">
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

<div class="form-group">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
</div>
