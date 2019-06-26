<?php

namespace common\components\tour;

class CommonTour {

	const SOURCE_BILETUR = 0;

	/** @var int` */
	public $source;

	/** @var string */
	public $countryId;

	/** @var string */
	public $cityId;

	/** @var string */
	public $title;

	/** @var string */
	public $beginDate;
	const ATTR_BEGIN_DATE = 'beginDate';

	/** @var string */
	public $endDate;
	const ATTR_END_DATE = 'endDate';

	/** @var float */
	public $price;
	const ATTR_PRICE = 'price';

}