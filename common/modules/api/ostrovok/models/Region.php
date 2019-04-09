<?php

namespace common\modules\api\ostrovok\models;

/**
 * Регионы
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Region {
	/** @var \common\modules\api\ostrovok\models\MapCenter */
	public $center;
	public $id; //int
	public $country_code; //String
	public $country_en; //String
	public $name_ru; //String
	public $category; //String
	public $image; //object
	public $type; //String
	public $parent; //int
	public $is_searchable; //boolean
	public $name_en; //String
	public $country_ru; //String
	public $hotels_count; //int
	public $categories; //array(String)

	const TYPE_AIRPORT = 0;
	const TYPE_BUS_STATION = 1;
	const TYPE_CITY = 2;
	const TYPE_CONTINENT = 3;
	const TYPE_COUNTRY = 4;
	const TYPE_MULTI_CITY = 5;
	const TYPE_MULTI_REGION = 6;
	const TYPE_NEIGHBORHOOD = 7;
	const TYPE_POINT_OF_INTEREST = 8;
	const TYPE_PROVINCE = 9;
	const TYPE_RAILWAY_STATION = 10;
	const TYPE_STREET = 11;
	const TYPE_SUBWAY = 12;

	const TYPES = [
		'Airport'                         => self::TYPE_AIRPORT,
		'Bus Station'                     => self::TYPE_BUS_STATION,
		'City'                            => self::TYPE_CITY,
		'Continent'                       => self::TYPE_CONTINENT,
		'Country'                         => self::TYPE_COUNTRY,
		'Multi-City (Vicinity)'           => self::TYPE_MULTI_CITY,
		'Multi-Region (within a country)' => self::TYPE_MULTI_REGION,
		'Neighborhood'                    => self::TYPE_NEIGHBORHOOD,
		'Point of Interest'               => self::TYPE_POINT_OF_INTEREST,
		'Railway Station'                 => self::TYPE_RAILWAY_STATION,
		'Street'                          => self::TYPE_STREET,
		'Subway (Entrace)'                => self::TYPE_SUBWAY,
	];
}