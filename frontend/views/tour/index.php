<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\forms\tour\SearchForm $form
 *
 */

use yii\widgets\Pjax; ?>
<div class="content-header text-center">
	<h1>Поиск тура</h1>
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
				<?= $this->render('_search-result', ['tours' => $form->result]) ?>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>