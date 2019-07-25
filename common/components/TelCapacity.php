<?php

namespace common\components;

use yii\base\Component;

class TelCapacity extends Component {

	const OP_MTS = 0;
	const OP_ROSTELEKOM = 1;
	const OP_BEELINE = 2;
	const OP_TELE_2 = 3;
	const OP_MEGAPHONE = 4;

	const OP_NAMES = [
		'ООО "Т2 Мобайл"'             => self::OP_TELE_2,
		'ПАО "Ростелеком"'            => self::OP_ROSTELEKOM,
		'ПАО "Вымпел-Коммуникации"'   => self::OP_BEELINE,
		'ПАО "Мобильные ТелеСистемы"' => self::OP_MTS,
		'ПАО "МегаФон"'               => self::OP_MEGAPHONE
	];

	const COLLECTION_CAPACITY = 'tel_capacity';

	public $code;
	const ATTR_CODE = 'code';

	public $begNumber;
	const ATTR_BEG_NUMBER = 'begNumber';

	public $endNumber;
	const ATTR_END_NUMBER = 'endNumber';

	public $operator;
	const ATTR_OPERATOR = 'operator';

	public $originOperator;
	const ATTR_ORIGIN_OPERATOR = 'originOperator';

	public $city;
	const ATTR_CITY = 'city';

	public $region;
	const ATTR_REGION = 'region';
}