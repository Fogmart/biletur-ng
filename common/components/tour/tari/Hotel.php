<?php

namespace common\components\tour\tari;

/**
 * Класс отелей Таритур
 *
 * @package common\components\tour\tari
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Hotel {
	/** @var int */
	public $id;
	const ATTR_ID = 'id';

	/** @var string */
	public $Name;
	const ATTR_NAME = 'Name';

	/** @var string */
	public $HotelCategory;

	/** @var int */
	public $CityID;
	const ATTR_CITY_ID = 'CityID';

	/** @var string */
	public $Description;

	/** @var int */
	public $BoardType;
}