<?php

namespace common\modules\api\tripster\components\objects;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class Country {
	/** @var int */
	public $id;
	/** @var string */
	public $name_ru;
	/** @var string */
	public $name_en;
	/** @var string */
	public $currency;
	/** @var string */
	public $in_obj_phrase;
	/** @var int */
	public $experience_count;
	/** @var string */
	public $url;
}