<?php

namespace common\components\hotels;

use yii\base\Component;

class CommonCancellationInfo extends Component {
	/** @var \common\components\hotels\CommonPolicies */
	public $policies;

	/** @var string Дата, до которой возможна бесплатная отмена (может быть null - отсутствие бесплатной отмены). Время указано в UTC+0. */
	public $freeCancellationBefore;
}