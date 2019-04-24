<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \yii\web\View                   $this
 * @var \common\forms\hotels\SearchForm $form
 */
?>
<div class="loading-widget" style="display: none;"></div>
<?= \common\base\helpers\Dump::d($form->result)?>