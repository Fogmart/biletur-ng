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
 * @var bool                                     $isNewMessage
 */
header('Access-Control-Allow-Origin: *');
?>

<?php Pjax::begin(['id' => 'message-form']); ?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
<?= $form->field($model, $model::ATTR_OBJECT_ID)->hiddenInput()->label(false) ?>
<?= $form->field($model, $model::ATTR_OBJECT)->hiddenInput()->label(false) ?>
<?= $form->field($model, $model::ATTR_USER_NAME)->hiddenInput()->label(false) ?>
<?= $form->field($model, $model::ATTR_MESSAGE)->textarea(['rows' => 6]) ?>
    <div class="form-group">
		<?= Html::submitButton(('Отправить'), ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
<?php Pjax::begin(['id' => 'messages']); ?>
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
                <td class="<?= ($message->isMine ? 'messages-my-message' : 'messages-foreign-message') ?>">
                    <i class="<?= ($message->isMine ? 'glyphicon glyphicon-export' : 'glyphicon glyphicon-import') ?>"> </i> <?= DateHelper::intlFormat(strtotime($message->insert_stamp)) ?>, <b><?= \yii\helpers\HtmlPurifier::process($message->user_name) ?></b>
                </td>
            </tr>
            <tr>
                <td>
                    <p><?= \yii\helpers\HtmlPurifier::process($message->message) ?></p>
                </td>
            </tr>
		<?php endforeach ?>
    </table>
<?php endif ?>
<?php Pjax::end(); ?>

<?php
$this->registerCss('
.messages-my-message {
    background-color: #dcdcdc;
}
.messages-foreign-message {
    background-color: #dff0d8;
}
');

$this->registerJs('
$("#message-form").on("pjax:end", function() {
            $.pjax.reload({container:"#messages"});
        });
  
    var timerId = setInterval(function() {
         $.pjax.reload({container:"#messages"});
    }, 5000);
    
    
');