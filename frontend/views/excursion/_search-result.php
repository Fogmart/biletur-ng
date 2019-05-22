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
    <div class="list">
		<?php foreach ($form->result as $excursion): ?>

		<?php endforeach ?>
    </div>
</div>