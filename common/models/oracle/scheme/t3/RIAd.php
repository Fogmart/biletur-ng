<?php

namespace common\models\oracle\scheme\t3;

use common\models\oracle\scheme\DspBaseModel;


/**
 * @author isakov.v
 *
 * Наверное, что-то связанное с рекламой/отображением туров в каталоге
 *
 * Поля таблицы:
 *
 * @property int    $ID
 * @property int    $TMID
 * @property string $BEGDATE
 * @property string $ENDDATE
 * @property int    $VIEWCNT
 * @property int    $TOURZONEID
 * @property int    $ON_TOP
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 */
class RIAd extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.RI_AD}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 1;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}
}