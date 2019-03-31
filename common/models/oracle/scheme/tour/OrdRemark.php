<?php

namespace common\models\scheme\tour;

use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ORDREMARK
 *
 * Поля таблицы:
 * @property string $ORDID
 * @property string $REMARK
 *
 *
 */
class OrdRemark extends ActiveRecord implements InvalidateModels {
	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{TOUR.ORDREMARK}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 12;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'ROWNUM';
	}

}