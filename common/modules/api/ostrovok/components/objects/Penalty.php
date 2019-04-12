<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\modules\api\ostrovok\components\objects;


use yii\base\Component;

class Penalty extends Component {
	/** @var string */
	public $amount;
	/** @var null|float */
	public $percent;
	/** @var string */
	public $currency_code;
	/** @var string */
	public $currency_rate_to_rub;
}