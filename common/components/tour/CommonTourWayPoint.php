<?php

namespace common\components\tour;

/**
 * Точки маршрута тура
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonTourWayPoint {
	/** @var string */
	public $country;
	const ATTR_COUNTRY = 'country';

	/** @var string */
	public $cityId;
	const ATTR_CITY_ID = 'cityId';

	/** @var string */
	public $cityName;
	const ATTR_CITY_NAME = 'cityName';

	/** @var int Номер точки маршрута в туре */
	public $number;
	const ATTR_NUMBER = 'number';

	/** @var int Кол-во дней(?) */
	public $daysCount;
	const ATTR_DAYS_COUNT = 'daysCount';

	/** @var string Изображение флага */
	public $countryFlagImage;
	const ATTR_COUNTRY_FLAG_IMAGE = 'countryFlagImage';
}