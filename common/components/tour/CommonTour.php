<?php

namespace common\components\tour;

use common\models\ObjectFile;

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

	/** @var array Минимальная[0]/максимальная[1] цена тура */
	public $priceMinMax;
	const ATTR_PRICE_MIN_MAX = 'priceMinMax';

	/** @var string */
	public $imageOld;
	const ATTR_IMAGE_OLD = 'imageOld';

	/** @var string */
	public $image;
	const ATTR_IMAGE = 'image';

	/** @var string Описание */
	public $description;
	const ATTR_DESCRIPTION = 'description';

	/** @var \common\components\tour\CommonTourWayPoint[] */
	public $wayPoints;
	const ATTR_WAY_POINTS = 'wayPoints';

	/** @var \common\components\tour\CommonLap[] */
	public $activeLaps;
	const ATTR_ACTIVE_LAPS = 'activeLaps';

	/**
	 * Получение изображения, если привязано
	 *
	 * @return string|null
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getImage() {
		$objectFile = ObjectFile::findOne([ObjectFile::ATTR_OBJECT => static::class . '_' . $this->source, ObjectFile::ATTR_ID => $this->sourceId]);

		if (null === $objectFile) {
			return null;
		}

		return $objectFile->filename;
	}
}