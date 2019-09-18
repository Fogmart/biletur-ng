<?php

namespace common\modules\api\etm\response\offers\entities;

/**
 * Информация о рейсе
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class FlightInfo {
	/** @var string */
	public $date;

	/** @var string */
	public $departure_country;

	/** @var string */
	public $departure_city;

	/** @var string */
	public $departure_airport;

	/** @var string */
	public $departure_terminal;

	/** @var string */
	public $departure_local_time;

	/** @var string */
	public $departure_timezone;

	/** @var string */
	public $arrival_country;

	/** @var string */
	public $arrival_city;

	/** @var string */
	public $arrival_airport;

	/** @var string */
	public $arrival_terminal;

	/** @var string */
	public $arrival_local_time;

	/** @var string */
	public $arrival_timezone;

	/** @var string */
	public $flight_number_print;

	/** @var string */
	public $duration_formated;

	/** @var string */
	public $stop_time; //String
}