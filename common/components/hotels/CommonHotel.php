<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\components\hotels;


use yii\base\Component;

class CommonHotel extends Component {
	/** @var int Источник данных(из какого API) */
	public $sourceApi;

	/** @var string Идентификатор отеля*/
	public $id;

	/** @var string Название отеля*/
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

	/** @var \common\components\hotels\CommonRate[] */
	public $rates;
}