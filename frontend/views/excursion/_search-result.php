<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                      $this
 * @var \common\forms\excursion\SearchForm $form
 */
?>
	<div class="result">
		<div class="loading-widget" style="display: none;"></div>
		<div class="list excursion-list">
			<?= $this->render('__excursions', ['excursions' => $form->result]) ?>
		</div>
	</div>
<?php
$this->registerJs('$(this).searchExcursionPlugin();');
$this->registerJs('$(this).commonPlugin();');
?>