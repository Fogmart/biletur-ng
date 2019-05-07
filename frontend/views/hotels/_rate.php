<?php
use common\modules\api\ostrovok\components\OstrovokApi;
use common\base\helpers\StringHelper;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\components\hotels\CommonRate $rate
 */
?>



<h4><?= $rate->roomTitle ?></h4> <br>
<h5><?= StringHelper::formatPrice($rate->price, '&#8381;') ?> </h5> <br>
Питание: <?= $rate->meal ?> <br>
<?php if (null !== $rate->roomInfo): ?>
	<?php foreach ($rate->roomInfo->images as $image): ?>
        <img class="img-rounded" src="<?= strtr($image, [
			'{size}' => OstrovokApi::IMAGE_SIZE_100X100
		]) ?>">
	<?php endforeach ?>
<?php endif ?>

<?php \common\base\helpers\Dump::d($rate) ?>
<hr>
