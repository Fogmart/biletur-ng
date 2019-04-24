<?php
namespace common\components\hotels;

use yii\base\Component;

class CommonRate extends Component {

	/** @var int Источник данных(из какого API) */
	public $sourceApi;

	/** @var string Идентификатор отеля*/
	public $hotelId;

	/** @var string Название отеля*/
	public $hotelName;

	/** @var string Сайт отеля */
	public $hotelPage;

	/** @var string Название номера */
	public $roomTitle;

	/** @var string Размер номера */
	public $roomSize;

	/** @var bool */
	public $noneRefundable;

	/** @var \common\components\hotels\CommonBedPlaces */
	public $bedPlaces;

	/** @var string */
	public $availabilityHash;

	/** @var string */
	public $bookHash;

	/** @var \common\components\hotels\CommonCancellationInfo */
	public $cancellationInfo;

	/** @var array Список цен по дням */
	public $dailyPrices;

	/** @var string Тип питания */
	public $meal;

	/** @var array Изображения номера */
	public $images;

	/** @var string */
	public $currency;

	/** @var float */
	public $price;

	/** @var string */
	public $description;

	/** @var \common\components\hotels\CommonAmenities[] */
	public $amenities;

	/** @var array Список удобств для фильтрации */
	public $filters;

	/** @var \common\components\hotels\CommonPaymentOptions Способы оплаты */
	public $paymentOptions;
}