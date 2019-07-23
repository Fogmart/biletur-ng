<?php

namespace common\components\tour\tari;

/**
 * Класс городов Таритур
 *
 * @package common\components\tour\tari
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class City {
	/** @var int */
	public $id;
	const ATTR_ID = 'id';

	/** @var string */
	public $name;
	const ATTR_NAME = 'name';

	/** @var int */
	public $CountryID;
	const ATTR_COUNTRY_ID = 'CountryID';
}