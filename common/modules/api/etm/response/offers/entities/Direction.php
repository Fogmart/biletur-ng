<?php

namespace common\modules\api\etm\response\offers\entities;

/**
 * Ответ при запросе предложений: направления
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Direction {
	/** @var int */
	public $dir_number;

	/** @var string */
	public $date;

	/** @var string */
	public $departure_code;

	/** @var string */
	public $departure_name;

	/** @var string */
	public $departure_country;

	/** @var string */
	public $arrival_code;

	/** @var string */
	public $arrival_name;

	/** @var string */
	public $arrival_country;
}