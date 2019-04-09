<?php
namespace common\modules\api\ostrovok\components\objects;

class PaymentType {
	public $is_need_credit_card_data; //boolean
	public $currency_code; //String
	public $is_need_cvc; //boolean
	public $by; //String
	public $type; //String
	public $amount; //String
	public $vat_included; //boolean
}