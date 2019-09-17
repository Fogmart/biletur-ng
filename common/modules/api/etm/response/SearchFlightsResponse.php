<?php

namespace common\modules\api\etm\respone;

/**
 * Ответ при поиске авиарейсов
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SearchFlightsResponse extends BaseResponse {
	/** @var int ID запроса */
	public $request_id;
}