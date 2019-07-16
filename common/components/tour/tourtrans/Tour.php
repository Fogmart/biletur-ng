<?php

namespace common\components\tour\tourtrans;

/**
 * Класс для тура Туртранса
 *
 * @package common\components\tour\tourtrans
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Tour {
	/** @var string */
	public $url;

	/** @var int */
	public $id;

	/** @var string */
	public $tourCode;

	/** @var string */
	public $title;

	/** @var string */
	public $image;

	/** @var int */
	public $duration;

	/** @var int */
	public $nightMoves;

	/** @var int */
	public $commission;

	/** @var int */
	public $minPrice;

	/** @var string */
	public $currency;

	/** @var string */
	public $route;

	/** @var string[] */
	public $countries;

	/** @var string */
	public $visa;

	/** @var string */
	public $include;

	/** @var string */
	public $freeFormula;

	/** @var \common\components\tour\tourtrans\Service[] */
	public $additional;

	/** @var \common\components\tour\tourtrans\Discount[] */
	public $discounts;

	/** @var \common\components\tour\tourtrans\TourDay[] */
	public $tourDays;

	/** @var \common\components\tour\tourtrans\TourDate[] */
	public $tourDates;
}