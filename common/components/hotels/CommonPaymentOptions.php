<?php

namespace common\components\hotels;


use yii\base\Component;

class CommonPaymentOptions extends Component {
	/** @var \common\components\hotels\CommonPaymentType[]  Массив с типом оплаты тарифа */
	public $paymentTypes;
}