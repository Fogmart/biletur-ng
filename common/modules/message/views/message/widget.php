<?php

use common\base\helpers\DateHelper;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\widgets\Pjax;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                            $this
 * @var \common\modules\message\models\Message   $model
 * @var \common\modules\message\models\Message[] $messages
 * @var array                                    $errors
 *
 */
?>

<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

<?= $form->field($model, $model::ATTR_OBJECT_ID)->hiddenInput()->label(false) ?>
<?= $form->field($model, $model::ATTR_OBJECT)->hiddenInput()->label(false) ?>
<?= $form->field($model, $model::ATTR_USER_NAME)->hiddenInput()->label(false) ?>
<?= $form->field($model, $model::ATTR_MESSAGE)->textarea(['rows' => 6]) ?>
<div class="form-group">
	<?= Html::submitButton(('Добавить'), ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
<?php if (count($errors) > 0): ?>
    <div class="error-summary">
		<?php foreach ($errors as $index => $error): ?>
            <p><?= $error[0] ?></p>
		<?php endforeach ?>
    </div>
<?php endif ?>
<?php if (count($messages) > 0): ?>
    <table class="table table-bordered table-condensed table-hover table-striped">
		<?php foreach ($messages as $message): ?>
            <tr>
                <td bgcolor="#dcdcdc">
					<?= DateHelper::intlFormat(strtotime($message->insert_stamp)) ?>, <b><?= $message->user_name ?></b>
                </td>
            </tr>
            <tr>

                <td>
                    <p><?= $message->message ?></p>
                </td>
            </tr>
		<?php endforeach ?>
    </table>
<?php endif ?>
<?php Pjax::end(); ?>
