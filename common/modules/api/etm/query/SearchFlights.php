<?php

namespace common\modules\api\etm\query;
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class SearchFlights {

	/** @var \common\modules\api\etm\Directions[] */
	public $directions;

	/** @var int */
	public $adult_qnt;

	/** @var int */
	public $child_qnt;

	/** @var int */
	public $infant_qnt;

	/** @var int ID тревел-политики */
	public $travel_policy_id;

	/** @var int ID поездки */
	public $one_order_id;

	/** @var string Льготная категория */
	public $passenger_category;

	/** @var string Выбранный класс обслуживания */
	public $class;

	/** @var bool Искать только прямые рейсы или любые */
	public $direct;

	/** @var bool Искать рейсы ±1 день от выбранной даты или только на выбранную дату */
	public $flexible;

	/** @var int Максимальная цена */
	public $max_price;

	/** @var string[] Предпочитаемые авиакомпании */
	public $airlines;

}