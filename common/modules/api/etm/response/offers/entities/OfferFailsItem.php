<?php

namespace common\modules\api\etm\response\offers\entities;

/**
 * Штрафы
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class OfferFailsItem {
	/** @var string */
	public $component_name;

	/** @var \common\modules\api\etm\response\offers\entities\OfferFailsComponentValue[] */
	public $component_value;
}