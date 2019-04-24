<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\components\hotels;

use yii\base\Component;

class CommonPolicies extends Component {
	/** @var common\components\hotels\Penalty */
	public $penalty;
	/** @var null|string */
	public $startAt;
	/** @var null|string */
	public $endAt; //object
}