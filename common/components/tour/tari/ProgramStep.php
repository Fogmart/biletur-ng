<?php

namespace common\components\tour\tari;

/**
 * Класс шага программы тура Таритур
 *
 * @package common\components\tour\tari
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class ProgramStep {
	/** @var int */
	public $Day;
	const ATTR_DAY = 'Day';

	/** @var int */
	public $WeekDay;
	const ATTR_WEEK_DAY = 'WeekDay';

	/** @var string */
	public $Description;
	const ATTR_DESCRIPTION = 'Description';

}