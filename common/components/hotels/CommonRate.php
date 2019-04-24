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

	public $noneRefundable;

	/** @var \common\components\hotels\CommonBadPlaces */
	public $badPlaces;

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


}