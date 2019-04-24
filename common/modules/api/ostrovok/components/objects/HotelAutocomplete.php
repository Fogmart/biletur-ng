<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

/**
 *
 * Класс отеля из автокопмплита
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class HotelAutocomplete extends Component {
	/** @var int */
	public $id;

	/** @var string */
	public $name;

	/** @var string */
	public $region_name;
}