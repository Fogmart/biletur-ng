<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                   $this
 * @var \common\forms\hotels\SearchForm $form
 */
?>
<div class="result">
    <div class="loading-widget" style="display: none;"></div>
    <div class="list">
		<?php foreach ($form->result as $hotel): ?>
            <div class="col-xs-12">
                <h2><?= $hotel->name ?></h2>
                <img src="<?= $hotel->image ?>">
            </div>

			<?php foreach ($hotel->rates as $rate): ?>

			<?php endforeach ?>
		<?php endforeach ?>
    </div>
</div>