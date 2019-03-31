<?php

namespace common\models\oracle\scheme\t3;

use common\models\oracle\scheme\DspBaseModel;
use common\models\oracle\scheme\sns\DspOrgs;
use common\interfaces\InvalidateModels;

/**
 * @author isakov.v
 *
 * Модель описания туров из схемы T3
 *
 * Поля таблицы:
 *
 * @property int                            $ITMID
 * @property string                         $TOPERID
 * @property string                         $DESCRIPTION
 * @property string                         $INCOST
 * @property string                         $EXTRACOST
 * @property string                         $NEEDDOCS
 * @property string                         $EXTRAINFO
 * @property string                         $REMARKS
 * @property string                         $URL_DESCR
 * @property string                         $URL_IMG
 * @property string                         $LANGCODE
 * @property string                         $CID
 * @property string                         $MAINCNTRYID
 * @property string                         $FULLDESCRIPT
 *
 * @property \common\models\scheme\sns\Orgs $tourOperator
 *
 */
class RITour extends DspBaseModel {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.RI_TOUR}}';
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
		return 'ROWNUM';
	}

	/**
	 * Связь с оператором тура
	 * @return \common\models\oracle\scheme\sns\DspOrgs
	 */
	public function getTourOperator() {
		return $this->hasOne(DspOrgs::class, ['ID' => 'TOPERID']);
	}
}