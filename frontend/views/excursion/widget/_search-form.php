<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                      $this
 * @var \common\forms\excursion\SearchForm $form
 */

use frontend\controllers\ExcursionController;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\web\JsExpression;

?>
<?php $htmlForm = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
<?= $htmlForm->field($form, $form::ATTR_PAGE)->hiddenInput()->label(false) ?>
<?= $htmlForm->field($form, $form::ATTR_CITY_TAG)->hiddenInput()->label(false) ?>
<?= $htmlForm->field($form, $form::ATTR_CITY_NAME)->hiddenInput()->label(false) ?>
<?php ActiveForm::end(); ?>
