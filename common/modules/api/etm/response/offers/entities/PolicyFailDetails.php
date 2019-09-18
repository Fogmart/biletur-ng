<?php

namespace common\modules\api\etm\response\offers\entities;

/**
 * Штрафы
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class PolicyFailDetails {

	/** @var \common\modules\api\etm\response\offers\entities\LegFailsItem[] */
	public $leg_fails;

	public $offer_fails;

}