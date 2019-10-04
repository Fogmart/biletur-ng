<?php

use frontend\controllers\ExcursionController;
use yii\widgets\Pjax;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                      $this
 * @var \common\forms\excursion\SearchForm $form
 */
?>
<div class="load-excursion-url" data-url="<?= ExcursionController::getActionUrl(ExcursionController::ACTION_LOAD) ?>"></div>
<?php Pjax::begin(); ?>
<div class="row">
	<div class="col-xs-12">
		<div class="block-panel filter-panel">
			<img class="block-logo" src="/images/excursion-logo.svg" height="30"> <img class="v-line" src="/images/v-line.svg" height="30">
			<h2>Экскурсии</h2>
			<?= $this->render('_search-form', ['form' => $form]) ?>
		</div>
		<div class="hide-filters-block">
			Скрыть доп.фильтры
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
<div class="row">
	<div class="col-xs-12 text-center">
		<button class="btn btn-lg btn-show-more">Показать еще</button>
	</div>
</div>
