<?php

namespace common\modules\api\etm\response\offers\entities;

/**
 * Ответ при запросе предложений: список предложений - сегмент
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Segment {

	/** @var int */
	public $segment_id;

	/** @var string */
	public $buy_id;

	/** @var bool */
	public $is_policy_fail;

	/** @var \common\modules\api\etm\response\offers\entities\PolicyFailDetails */
	public $policy_fail_details;

	/** @var int */
	public $dir_number;

	/** @var string */
	public $flight_number;

	/** @var string */
	public $flight_carrier_code;

	/** @var string */
	public $flight_carrier_name;

	/** @var string */
	public $departure_airport;

	/** @var int */
	public $departure_timestamp;

	/** @var string */
	public $arrival_airport;

	/** @var int */
	public $arrival_timestamp;

	/** @var string */
	public $duration_formated;

	/** @var int */
	public $duration_minutes;

	/** @var int */
	public $stops;

	/** @var \common\modules\api\etm\response\offers\entities\FlightInfo[] */
	public $flights_info;

	/** @var string */
	public $tarif;

	/** @var string */
	public $class;

	/** @var string */
	public $price;

	/** @var string */
	public $baggage;
}