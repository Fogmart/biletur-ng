<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

class PaymentType extends Component {
	/** @var string */
	public $type;
	/** @var bool */
	public $is_need_credit_card_data;
	/** @var float */
	public $amount;
	/** @var string */
	public $currency_code;
	/** @var bool */
	public $vat_included;
	/** @var float */
	public $vat_value;
	/** @var bool */
	public $is_need_cvc;

	public $tax_data;
	/** @var string */
	public $by;
	/** @var \common\modules\api\ostrovok\components\objects\Perk[] */
	public $perks;
}