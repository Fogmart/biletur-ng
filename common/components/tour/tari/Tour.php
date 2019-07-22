<?php

namespace common\components\tour\tari;

/**
 * Класс тура ТариТур
 *
 * @package common\components\tour\tari
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Tour {
	/** @var string */
	public $tourName;

	/** @var string */
	public $tourDate;

	/** @var string */
	public $price;

	/** @var int */
	public $mealId;

	/** @var bool */
	public $ticketsIncluded;
}