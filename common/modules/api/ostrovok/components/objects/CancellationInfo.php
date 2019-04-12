<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

class CancellationInfo extends Component {
	/** @var \common\modules\api\ostrovok\components\objects\Policies */
	public $policies;
	/** @var string */
	public $free_cancellation_before;
}