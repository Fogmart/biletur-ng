<?php

namespace common\modules\api\etm\response\offers;

use common\modules\api\etm\response\BaseResponse;

/**
 * Ответ при запросе предложений
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class OffersResponse extends BaseResponse {
	/** @var string Статус запроса */
	public $status;

	/** @var string */
	public $message;

	/** @var bool */
	public $is_round;

	/** @var bool */
	public $is_multi;

	/** @var string */
	public $currency;

	/** @var string[] */
	public $available_currencies;

	/** @var string */
	public $sort;

	/** @var \common\modules\api\etm\response\offers\entities\Direction[] */
	public $directions;

	/** @var \common\modules\api\etm\response\offers\entities\Offer[] */
	public $offers;


	const STATUS_IN_PROCESS = 'inProcess';
	const STATUS_READY = 'ready';
	const STATUS_ERROR = 'error';
}