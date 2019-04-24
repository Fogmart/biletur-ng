<?php
namespace common\components\hotels;

use yii\base\Component;

class CommonPaymentType extends Component {

	/** @var string */
	public $type;

	/** @var bool */
	public $isNeedCreditCardData;

	/** @var float */
	public $amount;

	/** @var string */
	public $currencyCode;

	/** @var bool */
	public $vatIncluded;

	/** @var float */
	public $vatValue;

	/** @var bool */
	public $isNeedCvc;

	public $taxData;
	/** @var string */
	public $by;

	/** @var \common\components\hotels\CommonPerk[] */
	public $perks;
}