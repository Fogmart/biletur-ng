<?php

namespace common\modules\api\etm\query;
/**
 * Запрос предложений по идентификатору поискового запроса
 *
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class Offers {
	/** @var  int ID запроса */
	public $request_id;

	/** @var string */
	public $currency;

	/** @var string */
	public $sort;

	const SORT_PRICE = 'price';
	const SORT_PROFIT = 'profit';
	const SORT_DURATION = 'duration';
}