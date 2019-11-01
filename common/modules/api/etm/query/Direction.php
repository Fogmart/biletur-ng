<?php

namespace common\modules\api\etm\query;

/**
 * Класс для направления при поиске предложений рейсов
 *
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class Direction {
	/** @var string` */
	public $departure_code;

	/** @var string */
	public $arrival_code;

	/** @var string */
	public $date;

	/** @var string */
//	public $time; //под вопросом

	/** @var array Время суток для вылета */
	/*const TIME = [
		'M',
		'N',
		'A',
		'E'
	];*/
}