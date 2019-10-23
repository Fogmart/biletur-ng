<?php

namespace common\modules\api\etm\query;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class Directions {
	/** @var string` */
	public $departure_code;

	/** @var string */
	public $arrival_code;

	/** @var string */
	public $date;

	/** @var string */
	public $time;

	/** @var array Время суток для вылета */
	const TIME = [
		'M',
		'N',
		'A',
		'E'
	];
}