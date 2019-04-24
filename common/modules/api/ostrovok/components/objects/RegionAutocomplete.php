<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

/**
 *
 * Класс региона из автокомплита
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class RegionAutocomplete extends Component {
	/** @var int */
	public $id;

	/** @var string */
	public $name;

	/** @var string */
	public $country;
}