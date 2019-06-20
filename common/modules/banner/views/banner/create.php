<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\pages\models\Page */

$this->title = 'Новый баннер';
$this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
