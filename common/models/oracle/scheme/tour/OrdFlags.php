<?php

namespace common\models\scheme\tour;

use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ORdFlags
 *
 * Поля таблицы:
 * @property string $ORDID
 * @property string $FLAGID
 * @property string $WHOUPD
 * @property string $WHNUPD
 *
 *
 */
class OrdFlags extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{TOUR.ORD_FLAGS}}';
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