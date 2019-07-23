<?php

namespace common\components\tour\tari;

/**
 * Класс курортов Таритур
 *
 * @package common\components\tour\tari
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Resort {
	/** @var int */
	public $id;
	const ATTR_ID = 'id';

	/** @var string */
	public $name;
	const ATTR_NAME = 'name';

	/** @var int */
	public $countryId;
	const ATTR_COUNTRY_ID = 'countryId';

	/** @var string */
	public $countryName;
	const ATTR_COUNTRY_NAME = 'countryName';

	/** @var int */
	public $bileturCityId;
	const ATTR_BILETUR_CITY_ID = 'bileturCityId';
}