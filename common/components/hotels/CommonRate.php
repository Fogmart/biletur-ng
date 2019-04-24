<?php
namespace common\components\hotels;

use yii\base\Component;

class CommonRate extends Component {

	/** @var int */
	public $sourceApi;

	/** @var string */
	public $hotelId;

	/** @var string */
	public $hotelName;

	/** @var string */
	public $hotelPage;

	/** @var string */
	public $roomTitle;

	/** @var string */
	public $roomSize;

	/** @var bool */
	public $noneRefundable;

	/** @var \common\components\hotels\CommonBedPlaces */
	public $bedPlaces;

	/** @var string */
	public $availabilityHash;

	/** @var \common\components\hotels\CommonCancellationInfo */
	public $cancellationInfo;

	/** @var array */
	public $dailyPrices;

	/** @var string */
	public $meal;

	/** @var array */
	public $images;

	/** @var string */
	public $currency;

	/** @var float */
	public $price;

	/** @var string */
	public $description;

	/** @var \common\components\hotels\CommonAmenities[] */
	public $amenities;
}