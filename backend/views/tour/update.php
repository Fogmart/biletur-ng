<?php

use backend\controllers\UploadFileController;
use dosamigos\fileupload\FileUploadUI;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\modules\seo\models\Seo           $seo
 * @var \common\models\oracle\scheme\t3\RefItems $refItem
 */
$this->params['breadcrumbs'][] = ['label' => 'Туры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $refItem->NAME;

/** @var \common\models\ObjectFile $image */
$image = $refItem->getImage();
?>
    <div class="seo-form">
		<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($seo, $seo::ATTR_SEO_TITLE)->textInput(['maxlength' => true]) ?>

		<?= $form->field($seo, $seo::ATTR_SEO_DESCRIPTION)->textInput(['maxlength' => true]) ?>

		<?= $form->field($seo, $seo::ATTR_SEO_KEYWORDS)->textInput(['maxlength' => true]) ?>

        <div class="form-group">
			<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
		<?php ActiveForm::end(); ?>
    </div>
    <div class="image-form">
		<?= FileUploadUI::widget([
			'model'         => $refItem,
			'attribute'     => $refItem::ATTR_FILE,
			'url'           => [
				'/upload-file/index',
				'objectName' => $seo->object,
				'objectId'   => $seo->object_id,
			],
			'gallery'       => false,
			'fieldOptions'  => [
				'accept'   => 'jpeg, jpg, png, gif',
				'multiple' => false,
			],
			'clientOptions' => [
				'maxFileSize' => 2000000,
			],
			'clientEvents'  => [
				'fileuploaddone' => 'function(e, data) { $(".fileupload-buttonbar").hide(); }',
				'fileuploadfail' => 'function(error, data) {
									        $(".file-upload-error").hide(); 
								        if (error) {
									            $(".file-upload-error").html(error);
									            $(".file-upload-error").show();
									        }
									        //console.log(error); console.log(data);
									}',
			]
			,
		]);
		?>
    </div>
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
                <a class="btn btn-danger btn-sm" href="<?= UploadFileController::getActionUrl(UploadFileController::ACTION_DELETE, ['id' => $image->id]) ?>">
                    Удалить
                </a>
            </td>
        </tr>
    </table>
<?php endif ?>