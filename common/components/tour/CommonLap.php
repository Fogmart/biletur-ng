<?php

namespace common\components\tour;

/**
 * Заезды тура
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonLap {

	/** @var int Идентификатор заезда в источнике */
	public $id;
	const ATTR_ID = 'id';

	/** @var string */
	public $startDate;
	const ATTR_START_DATE = 'startDate';

	/** @var string */
	public $endDate;
	const ATTR_END_DATE = 'endDate';
}