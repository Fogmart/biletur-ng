<?php

namespace common\components\tour\tourtrans;

class TourDate {
	/** @var string */
	public $date;

	/** @var int */
	public $placesLeft;

	/** @var \common\components\tour\tourtrans\Hotel[] */
	public $hotels;

	/** @var float */
	public $minPrice;
}