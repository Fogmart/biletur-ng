<?php

use common\modules\api\ostrovok\components\OstrovokApi;
use common\components\hotels\CommonHotel;
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 *
 * @var CommonHotel $hotel
 */
?>

<div class="col-xs-12">
    <h2><?= $hotel->name ?></h2>
</div>
<div class="col-xs-3">
    <img class="img-rounded" src="<?= strtr($hotel->image, [
		'{size}' => OstrovokApi::IMAGE_SIZE_240X240
	]) ?>">
</div>
<div class="col-xs-9">
    <h4>Описание отеля</h4>
    <span class="hotel-description"><?= $hotel->description ?></span>
</div>