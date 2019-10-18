<?php

use frontend\controllers\TourController;
use yii\widgets\Pjax;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\forms\tour\SearchForm $form
 *
 */
?>
<div class="load-tour-url" data-url="<?= TourController::getActionUrl(TourController::ACTION_LOAD) ?>"></div>
<?php Pjax::begin(); ?>
<div class="row">
	<div class="col-xs-12">
		<div class="block-panel filter-panel r-footer">
			<img class="block-logo" src="/images/excursion-logo.svg" height="30"> <img class="v-line" src="/images/v-line.svg" height="30">
			<h2>Туры</h2>
			<?= $this->render('_search-form', ['form' => $form]) ?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<?= $this->render('_search-result', ['tours' => $form->result]) ?>
	</div>
</div>
<?php Pjax::end(); ?>

