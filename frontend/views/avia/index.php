<?php

use yii\widgets\Pjax;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

?>
<?php Pjax::begin(); ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="block-panel">
				<img class="block-logo" src="/images/excursion-logo.svg" height="30"> <img class="v-line" src="/images/v-line.svg" height="30">
				<h2>Авиабилеты</h2>
				<?= $this->render('_search-form', ['form' => $form]) ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<?= $this->render('_search-result', ['form' => $form]) ?>
		</div>
	</div>
<?php Pjax::end(); ?>