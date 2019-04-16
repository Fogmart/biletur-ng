<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\seo\models\Seo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seo-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'seo_description')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'seo_keywords')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
		<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

	<?php ActiveForm::end(); ?>

</div>
