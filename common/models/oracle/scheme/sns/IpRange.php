<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель ёмкостей ip-адресов
 *
 * Поля таблицы:
 * @property int    $BEGRANGE
 * @property int    $ENDRANGE
 * @property int    $DELTA
 * @property string $OWNER
 * @property string $CITYID
 * @property string $CITY
 * @property string $REGION
 * @property string $DISTRICT
 */
class IpRange extends ActiveRecord {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.IPRANGE}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 24;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'ROWNUM';
	}
}