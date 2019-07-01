<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\modules\seo\models\Seo $seo
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