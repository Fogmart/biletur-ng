<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\components\hotels;


use yii\base\Component;

class CommonHotel extends Component {
	/** @var int Источник данных(из какого API) */
	public $sourceApi;

	/** @var string Идентификатор отеля */
	public $id;

	/** @var string Название отеля */
	public $name;

	/** @var string Описание */
	public $description;

	/** @var string Сайт отеля */
	public $page;

	/** @var string Главное изображение отеля */
	public $image;

	/** @var string[] Все изображения */
	public $images;

	/** @var string Адрес отеля */
	public $address;

	/** @var string тип отеля */
	public $kind;

	/** @var float координаты */
	public $latitude;

	/** @var float координаты */
	public $longitude;

	/** @var string Телефон */
	public $phone;

	/** @var int Кол-во звезд */
	public $rating;

	/** @var array Услуги */
	public $amenities;

	/** @var array Группы комнат */
	public $roomGroups;

	/** @var \common\components\hotels\CommonRate[] */
	public $rates;

	/**
	 * Типы отелей
	 *
	 */
	const HOTEL_TYPE_RESORT = 0;
	const HOTEL_TYPE_SANATORIUM = 1;
	const HOTEL_TYPE_GUESTHOUSE = 2;
	const HOTEL_TYPE_CASTLE = 3;
	const HOTEL_TYPEMINIHOTEL = 4;
	const HOTEL_TYPEHOTEL = 5;
	const HOTEL_BOUTIQUE_AND_DESIGN = 6;
	const HOTEL_APARTMENT = 7;
	const HOTEL_COTTAGES = 8;
	const HOTEL_VILLAS_BUNGALOS = 9;
	const HOTEL_FARM = 10;
	const HOTEL_CAMPING = 11;
	const HOTEL_HOSTEL = 12;
	const HOTEL_BNB = 13;

	/**
	 * Имена типов отелей
	 *
	 */
	const HOTEL_TYPES = [
		self::HOTEL_TYPE_RESORT         => 'Отель курортного типа',
		self::HOTEL_TYPE_SANATORIUM     => 'Санаторий',
		self::HOTEL_TYPE_GUESTHOUSE     => 'Гостевой дом',
		self::HOTEL_TYPEMINIHOTEL       => 'Мини-отель',
		self::HOTEL_TYPE_CASTLE         => 'Замок',
		self::HOTEL_BOUTIQUE_AND_DESIGN => 'Бутик-отель',
		self::HOTEL_APARTMENT           => 'Апартаменты',
		self::HOTEL_COTTAGES            => 'Коттеджи и дома',
		self::HOTEL_FARM                => 'Размещение в фермерском хозяйстве',
		self::HOTEL_VILLAS_BUNGALOS     => 'Виллы и бунгало',
		self::HOTEL_CAMPING             => 'Кемпинг (размещение в палатках)',
		self::HOTEL_HOSTEL              => 'Хостел',
		self::HOTEL_BNB                 => 'Койко-место с завтраком'
	];
}