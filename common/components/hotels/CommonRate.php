<?php
namespace common\components\hotels;

use yii\base\Component;

class CommonRate extends Component {
	/** @var string Название номера */
	public $roomTitle;

	/** @var string Размер номера */
	public $roomSize;

	/** @var int Тип комнаты для подгрузки информации*/
	public $roomTypeId;

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

	/** @var \common\components\hotels\CommonRoomInfo Информация о номере (изображения, удобства и тд)*/
	public $roomInfo;
}