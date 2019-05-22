<?php

use yii\widgets\Pjax;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                   $this
 * @var \common\forms\hotels\SearchForm $form
 */
?>
    <div class="content-header text-center">
        <h1>Поиск экскурсий</h1>
    </div>
<?php Pjax::begin(); ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="block-panel">
				<?= $this->render('_search-form', ['form' => $form]) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="block-panel">
				<?= $this->render('_search-result', ['form' => $form]) ?>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>