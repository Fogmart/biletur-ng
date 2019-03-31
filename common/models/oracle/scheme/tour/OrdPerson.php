<?php

namespace common\models\scheme\tour;

use common\components\helpers\OraHelper;
use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ORDPRSN
 *
 * Поля таблицы:
 * @property string $ID
 * @property string $ORDID
 * @property string $WIWID
 * @property string $LNAME
 * @property string $FNAME
 * @property string $MNAME
 * @property string $LNAME_EN
 * @property string $FNAME_EN
 * @property string $SEX
 * @property string $BIRTHDAY
 * @property string $PASTYPEID
 * @property string $PASTYPE
 * @property string $PASSERIA
 * @property string $PASNUM
 * @property string $PASISSD
 * @property string $PASEXPD
 * @property string $ADDRESS
 * @property string $COMMTYPEID
 * @property string $PHONETYPE
 * @property string $PHONE
 * @property string $COMMID
 * @property string $COMMNUM
 * @property string $REFQUOTID
 * @property string $ORDQUOTID
 * @property string $TICKETTYPE
 * @property string $HOTELID
 * @property string $HOTEL
 * @property string $ROOMTYPE
 * @property string $ROOMINFO
 * @property string $FEEDTYPE
 * @property string $FEEDOTHER
 * @property int    $NEEDVISA
 * @property int    $NEEDINSUR
 * @property string $TRNSFTYPE
 * @property string $INSRTYPELST
 * @property string $INSRDOCTYPE
 * @property string $INSRDOCNUM
 * @property string $EXCPROGRAM
 * @property string $TOUROPERID
 * @property string $TOURNUM
 * @property string $BLNKNUM
 * @property int    $ERRCOUNT
 * @property string $WHNCRT
 * @property string $WHOCRT
 * @property string $WHNCHNG
 * @property string $WHOCHNG
 * @property string $AUXSERVICE
 * @property string $PRSNSUM
 * @property string $EMAIL
 * @property string $IS_CLIENT
 *
 */
class OrdPerson extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{TOUR.ORDPRSN}}';
	}

	public function init() {
		parent::init();
		$this->ID = OraHelper::getAbzId(self::className());
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