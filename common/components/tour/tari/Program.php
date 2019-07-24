<?php

namespace common\components\tour\tari;

/**
 * Класс программы тура Таритур
 *
 * @package common\components\tour\tari
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Program {
	/** @var int */
	public $tourId;
	const ATTR_TOUR_ID = 'tourId';

	/** @var \common\components\tour\tari\ProgramStep[] */
	public $steps;
	const ATTR_STEPS = 'steps';
}