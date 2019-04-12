<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

/**
 * Налоги
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Tax extends Component {
	/** @var string */
	public $trans_key;
	/** @var bool */
	public $included_by_supplier;
	/** @var string */
	public $amount;
	/** @var string */
	public $currency_code;
}