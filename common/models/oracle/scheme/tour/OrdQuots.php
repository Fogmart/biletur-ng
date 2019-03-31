<?php

namespace common\models\scheme\tour;

use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ORDQUOTS
 *
 * Поля таблицы:
 * @property string $ID
 * @property string $ORDID
 * @property string $PARID
 * @property string $STORNOID
 * @property string $RAIDID
 * @property string $PRSNID
 * @property string $QUOTTYPE
 * @property string $REFQUOTID
 * @property string $SUPID
 * @property string $SUPNAME
 * @property string $SUPDPSTSUM
 * @property int    $ISTOUROPER
 * @property string $DOGTYPEID
 * @property string $DOGID
 * @property string $DOGNUM
 * @property string $ITEM
 * @property string $SUPORDNUM
 * @property string $BILLNUM
 * @property string $BILLDATE
 * @property string $BILLENDDATE
 * @property string $CRNCYSUM
 * @property string $CRNCY
 * @property string $QUOTSUM
 * @property string $CNVPCNT
 * @property string $CNVSUM
 * @property string $IBPCNT
 * @property string $IBPSUM
 * @property string $IBSSUM
 * @property string $I2BPCNT
 * @property string $I2BPSUM
 * @property string $I2BSSUM
 * @property string $EBPCNT
 * @property string $EBPSUM
 * @property string $EBSSUM
 * @property string $DSCNTID
 * @property string $DSCPCNT
 * @property string $DSCPSUM
 * @property string $DSCNTSUM
 * @property string $TOTPRICE
 * @property int    $PDQTY
 * @property string $UNIT
 * @property string $BONUSSUM
 * @property string $TOTSUM
 * @property string $FIXEDRATE
 * @property string $CRNCYRATE
 * @property int    $CRNCYDIV
 * @property int    $OURCNVTYPE
 * @property int    $OURCNVPCNT
 * @property int    $OURCNVSUM
 * @property string $TOTSUMRUB
 * @property string $WHOPAYAPRV
 * @property string $WHNPAYAPRV
 * @property int    $PAIDSUM
 * @property int    $CRNCYPAIDSUM
 * @property int    $NOTDOGSUM
 * @property string $FCTCSTSUM
 * @property string $FCTBNSSUM
 * @property string $VLDSTATUS
 * @property string $WHOVALID
 * @property string $AGDONEDATE
 * @property string $BKDONEDATE
 * @property string $CNCLDATE
 * @property string $CALCDATE
 * @property int    $ERRCOUNT
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOCHNG
 * @property string $WHNCHNG
 * @property string $FIXTYPE
 * @property string $RATEDATE
 * @property string $ITMID
 * @property string $ARCHDATE
 * @property string $ISCMPLX
 * @property string $CMPLXPARID
 * @property string $BILL_DOCARCHID
 * @property string $GIVEBEGDATE
 * @property string $GIVEENDDATE
 * @property string $FD_PREPDATE
 * @property string $SUPVATRATE
 * @property string $SUPVATSUM
 * @property string $OURVATRATE
 * @property string $OURVATSUM
 * @property string $TOTVATSUMRUB
 * @property string $EXTPAYCOST
 * @property string $LNKTYPEID
 * @property int    $FD_GENTYPEID
 * @property string $MTGTORGTYPE
 *
 */
class OrdQuots extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{TOUR.ORDQUOTS}}';
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
		return 'WHNCHNG';
	}
}