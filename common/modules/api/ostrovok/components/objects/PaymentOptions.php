<?php

namespace common\modules\api\ostrovok\components\objects;


use yii\base\Component;

class PaymentOptions extends Component {
	/** @var \common\modules\api\ostrovok\components\objects\PaymentType[] */
	public $payment_types;
}