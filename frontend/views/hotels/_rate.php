<?php
use common\modules\api\ostrovok\components\OstrovokApi;
use common\base\helpers\StringHelper;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var \common\components\hotels\CommonRate[] $rates
 */
?>
<h4><?= $rates[0]->roomTitle ?></h4> <br>
<?php foreach ($rates as $rate): ?>
    <?= StringHelper::formatPrice($rate->price, '&#8381;')?> <br>
<?php endforeach?>
<hr>
