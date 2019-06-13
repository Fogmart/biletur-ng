<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

/**
 *
 * Результат для автокомплита отелей и регионов
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Autocomplete extends Component {
	/** @var \common\modules\api\ostrovok\components\objects\HotelAutocomplete[] */
	public $hotels;

	/** @var \common\modules\api\ostrovok\components\objects\RegionAutocomplete[] */
	public $regions;
}