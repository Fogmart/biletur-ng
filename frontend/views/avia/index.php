<?php

use frontend\controllers\AviaController;
use yii\widgets\Pjax;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
?>
	<div class="load-offers-url" data-url="<?= AviaController::getActionUrl(AviaController::ACTION_GET_RESULT) ?>"></div>
	<div class="offer-request-id" data-id=""></div>
<?php Pjax::begin(); ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="block-panel filter-panel">
				<img class="block-logo" src="/images/excursion-logo.svg" height="30"> <img class="v-line" src="/images/v-line.svg" height="30">
				<h2>Авиабилеты</h2>
				<?= $this->render('_search-form', ['form' => $form]) ?>
			</div>
			<div class="hide-filters-block">
				Скрыть доп.фильтры
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<?= $this->render('_search-result', ['form' => $form]) ?>
		</div>
	</div>
<?php Pjax::end(); ?>