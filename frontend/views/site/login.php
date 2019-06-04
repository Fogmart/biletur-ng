<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Вход в личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="block-panel">
            <div class="site-login">
                <h1><?= Html::encode($this->title) ?></h1>
                <div class="row">
                    <div class="col-lg-5">
						<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

						<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

						<?= $form->field($model, 'password')->passwordInput() ?>

						<?= $form->field($model, 'rememberMe')->checkbox() ?>

                        <div style="color:#999;margin:1em 0">
                           Если вы забыли пароль вы можете <?= Html::a('восстановить его', ['site/request-password-reset']) ?>.
                            <br>
                            Не пришло письмо подтверждения? <?= Html::a('Отправить заново', ['site/resend-verification-email']) ?>
                        </div>

                        <div class="form-group">
							<?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>

						<?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>