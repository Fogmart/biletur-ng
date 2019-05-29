<?php

use frontend\assets\AppAsset;
use yii\widgets\Pjax;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                      $this
 * @var \common\forms\excursion\SearchForm $form
 * @var bool                               $needSearch
 */

?>
    <div class="content-header text-center">
        <h1><?= (null !== $form->cityName ? $form->cityName : '') ?></h1>
    </div>
	<?php Pjax::begin(); ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="block-panel">
				<?= $this->render('_search-form', ['form' => $form, 'needSearch' => $needSearch]) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="block-panel" style="min-height: 300px;">
				<?= $this->render('_search-result', ['form' => $form]) ?>
            </div>
        </div>
    </div>
    <?= $this->registerJs('$(this).widgetPlugin("sendDocHeightMsg");', \yii\web\View::POS_LOAD)?>
	<?php Pjax::end(); ?>

