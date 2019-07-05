<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use common\models\oracle\scheme\DspBaseModel;
use Yii;

/**
 * Модель Фотографий в ДСП
 *
 * @author isakov.v
 *
 * Модель городов
 *
 * Поля таблицы:
 * @property int    $ID
 * @property string $TITLE
 * @property string $ENTITLE
 * @property string $CATEGORY
 * @property string $KEYWORDS
 * @property string $ICONFNAME
 * @property string $FNAME
 * @property string $WHNCRT
 * @property string $WHOCRT
 * @property string $WHNCHNG
 * @property string $WHOCHNG
 */
class DspPhotos extends DspBaseModel {
	const ATTR_ID = 'ID';
	const ATTR_KEYWORDS = 'KEYWORDS';
	const ATTR_ICONFNAME = 'ICONFNAME';
	const ATTR_FILE_NAME = 'FNAME';
	const ATTR_WHNCRT = 'WHNCRT';

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.PHOTOS}}';
	}

	/**
	 * @inheritDoc
	 */
	public function getPrimaryKey($asArray = false) {
		return 'ID';
	}
}