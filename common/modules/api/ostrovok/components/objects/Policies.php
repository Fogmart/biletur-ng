<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

class Policies extends Component {
	/** @var \common\modules\api\ostrovok\components\objects\Penalty */
	public $penalty;
	/** @var null|string */
	public $start_at;
	/** @var null|string */
	public $end_at; //object
}