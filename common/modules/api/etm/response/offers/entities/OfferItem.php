<?php

namespace common\modules\api\etm\response\offers\entities;

/**
 * Ответ при запросе предложений: список предложений
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class OfferItem {
	/** @var float */
	public $min_price;

	/** @var \common\modules\api\etm\response\offers\entities\Segment[] */
	public $segments;

}