<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ORGGRPLST
 *
 * Поля таблицы:
 * @property string $ORGGRPLSTID
 * @property string $ORGGRPID
 * @property string $ORGID
 * @property string $BEGDATE
 * @property string $ENDDATE
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOCHNG
 * @property string $WHNCHNG
 * @property string $RANK
 *
 */
class DspOrgGrpLst extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ORGGRPLST}}';
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
		return 'WHNCHNG';
	}

}