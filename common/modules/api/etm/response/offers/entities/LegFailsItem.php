<?php

namespace common\modules\api\etm\response\offers\entities;

/**
 * Штрафы
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class LegFailsItem {

	/** @var int */
	public $leg_num;

	/** @var \common\modules\api\etm\response\offers\entities\LegFailsItemFails[] */
	public $fails;

}