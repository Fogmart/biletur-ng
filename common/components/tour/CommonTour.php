<?php

namespace common\components\tour;

class CommonTour {

	const SOURCE_BILETUR = 0;

	/** @var int` */
	public $source;

	/** @var int|string Идентификатор в системе источника */
	public $sourceId;
	const ATTR_SOURCE_ID = 'sourceId';

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

	/** @var string */
	public $image;
	const ATTR_IMAGE = 'image';

	/** @var string Описание */
	public $description;
	const ATTR_DESCRIPTION = 'description';

	/** @var \common\components\tour\CommonTourWayPoint[] */
	public $wayPoints;
	const ATTR_WAY_POINTS = 'wayPoints';

}