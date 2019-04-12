<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ADMREGS
 *
 * Поля таблицы:
 * @property string $ID
 * @property string $CODE
 * @property string $NAME
 * @property string $REGION
 * @property string $CITYID
 *
 */
class DspAdmRegs extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ADMREGS}}';
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