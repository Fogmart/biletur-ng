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

	/** @var string */
	public $Name;

	/** @var string */
	public $HotelCategory;

	/** @var int */
	public $CityID;

	/** @var string */
	public $Description;

	/** @var int */
	public $BoardType;
}