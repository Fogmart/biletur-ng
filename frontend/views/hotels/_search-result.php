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
				<?= $this->render('_hotel', ['hotel' => $hotel]) ?>
            </div>
            <div class="col-xs-12">
				<?php foreach ($hotel->rates as $id => $rates): ?>
					<?= $this->render('_rate', ['rates' => $rates]) ?>
				<?php endforeach ?>
            </div>
		<?php endforeach ?>
    </div>
</div>
<?php $this->registerJs('$(this).commonPlugin();'); ?>