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
		<?= \common\base\helpers\Dump::d($form->result) ?>
    </div>
</div>