<?php

namespace common\widgets;

use common\base\helpers\Dump;
use common\modules\api\ostrovok\components\ObjectLoader;
use common\modules\api\ostrovok\components\objects\BedPlaces;
use common\modules\api\ostrovok\components\objects\CancellationInfo;
use common\modules\api\ostrovok\components\objects\PaymentOptions;
use common\modules\api\ostrovok\components\objects\PaymentType;
use common\modules\api\ostrovok\components\objects\Perk;
use common\modules\api\ostrovok\components\objects\Rate;
use common\modules\api\ostrovok\components\objects\Tax;
use Yii;
use yii\base\Widget;

/**
 * Виджет поиска отелей
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class HotelSearchWidget extends Widget {

	public function run() {
		ObjectLoader::loadHotels();

	}
}