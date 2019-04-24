<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\components\hotels;

use yii\base\Component;

class CommonPenalty extends Component {
	/** @var string */
	public $amount;
	/** @var null|float */
	public $percent;
	/** @var string */
	public $currencyCode;
	/** @var string */
	public $currencyRateToRub;
}