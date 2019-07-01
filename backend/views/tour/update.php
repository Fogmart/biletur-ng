<?php

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
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="seo-form">
	<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($seo, $seo::ATTR_SEO_TITLE)->textInput(['maxlength' => true]) ?>

	<?= $form->field($seo, $seo::ATTR_SEO_DESCRIPTION)->textInput(['maxlength' => true]) ?>

	<?= $form->field($seo, $seo::ATTR_SEO_KEYWORDS)->textInput(['maxlength' => true]) ?>

    <div class="form-group">
		<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<div class="image-form">
	<?= FileUploadUI::widget([
		'model'     => $refItem,
		'attribute' => $refItem::ATTR_ID,

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