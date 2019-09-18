<?php

namespace common\modules\api\etm\response\offers\entities;

/**
 * Ответ при запросе предложений: предложения
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Offer {
	/** @var string */
	public $carrier_code;

	/** @var string */
	public $carrier_name;

	/** @var string */
	public $carrier_logo;

	/** @var float */
	public $min_price;

	/** @var \common\modules\api\etm\response\offers\entities\OfferItem[] */
	public $offers;
}