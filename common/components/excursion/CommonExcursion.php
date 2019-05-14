<?php

namespace common\components\excursion;

use yii\base\Component;

/**
 * Общий класс для приведения экскурсий к одному обьекту
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonExcursion extends Component {
	/** @var int Источник данных(из какого API) */
	public $sourceApi;

	/** @var string Идентификатор */
	public $id;

	/** @var string Название */
	public $name;

	/** @var string Краткое описание */
	public $annotation;

	/** @var string Описание */
	public $description;

	/** @var string Сайт */
	public $url;

	/** @var string Главное изображение отеля */
	public $image;

	/** @var string[] Все изображения */
	public $images;

	/** @var int Тип */
	public $type;

	/** @var bool Возможно ли моментальное бронирование */
	public $instantBooking;

	/** @var bool Подходит ли экскурсия детям */
	public $childFriendly;

	/** @var int Максимальное кол-во участников */
	public $maxPersons;

	/** @var int Продолжительность в часах */
	public $duration;

	/** @var \common\components\excursion\CommonPrice */
	public $price;

	/** @var int Кол-во отзывов */
	public $reviewCount;

	/** @var float Рэйтинг(кол-во звезд) */
	public $rating;

	/** @var int Индекс популярности */
	public $popularity;

	/** @var \common\components\excursion\CommonCity */
	public $city;

	const TYPE_PRIVATE = 0;
	const TYPE_GROUP = 1;

	const TYPE_NAMES = [
		self::TYPE_PRIVATE => 'Приватная',
		self::TYPE_GROUP   => 'Групповая',
	];
}