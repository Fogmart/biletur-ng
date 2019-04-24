<?php
namespace common\components\hotels;

use yii\base\Component;

class CommonCancellationInfo extends Component {
	/** @var \common\components\hotels\CommonPolicies */
	public $policies;
	/** @var string */
	public $freeCancellationBefore;
}