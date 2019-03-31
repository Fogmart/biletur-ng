<?php

namespace common\models\scheme\tour;

use common\interfaces\InvalidateModels;
use common\models\scheme\t3\RefItems;
use common\models\scheme\t3\RILaps;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ORDTOUR
 *
 * Поля таблицы:
 * @property string                                    $ID
 * @property string                                    $TOURID
 * @property string                                    $RAIDID
 * @property string                                    $PRICEID
 * @property string                                    $GRPID
 * @property string                                    $TOURNAME
 * @property string                                    $TOURTYPE
 * @property string                                    $DATE_BEG
 * @property string                                    $DATE_END
 * @property string                                    $TOPERID
 * @property string                                    $TOPERNAME
 * @property string                                    $GRPLEADER
 * @property string                                    $ESCORTLST
 * @property string                                    $ESCORTOTHR
 * @property int                                       $MINTOURIST
 * @property string                                    $MAINCNTRYID
 * @property string                                    $MAINCNTRYCODE
 * @property string                                    $ROUTE
 * @property string                                    $WP_BEG
 * @property string                                    $MEETTIME
 * @property string                                    $WAYPOINTS
 * @property string                                    $WP_END
 * @property int                                       $GTOURNUM
 * @property int                                       $GBLNKNUM
 * @property int                                       $MINTOURISTCNT
 * @property string                                    $ESTTOURPRICE
 * @property string                                    $ADVSUM
 * @property string                                    $INSR1ORGID
 * @property string                                    $INSR2ORGID
 * @property int                                       $INSRTOTCNT
 * @property int                                       $INSRDEFCNT
 * @property string                                    $PREDOGDATE
 * @property int                                       $TRNSPRTSUM
 * @property int                                       $ESTTOPRSUM
 * @property int                                       $ESTVISASUM
 * @property int                                       $ESTFINESUM
 * @property int                                       $OKUNID
 * @property int                                       $NUMDAYS
 * @property int                                       $NUMNIGHTS
 * @property int                                       $CARRYTYPE
 * @property string                                    $APNAME
 * @property string                                    $APPHONE
 * @property string                                    $AKNAME
 * @property string                                    $DEPTIME
 * @property string                                    $FLNUM
 * @property string                                    $WHOCRT
 * @property string                                    $WHNCRT
 * @property string                                    $WHOCHNG
 * @property string                                    $WHNCHNG
 * @property int                                       $BNDLHOTEL
 * @property int                                       $BNDLTRNSF
 * @property int                                       $BNDLATKT
 * @property int                                       $BNDLVISA
 * @property int                                       $BNDLMINSR
 * @property int                                       $BNDLRINSR
 * @property int                                       $BNDLAUX
 * @property string                                    $BNDLAUXITM
 * @property string                                    $TOURKIND
 * @property string                                    $INSR2TRF
 * @property string                                    $INSR2COST
 * @property string                                    $MAINCITYID
 *
 * @property-read \common\models\scheme\tour\OrdRemark $remark
 * @property-read \common\models\scheme\t3\RefItems    $tour
 * @property-read \common\models\scheme\t3\RILaps      $lap
 *
 */
class OrdTour extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{TOUR.ORDTOUR}}';
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

	public function getRemark() {
		return $this->hasOne(OrdRemark::className(), ['ORDID' => 'ID']);
	}

	public function getTour() {
		return $this->hasOne(RefItems::className(), ['ID' => 'TOURID']);
	}

	public function getLap() {
		return $this->hasOne(RILaps::className(), ['ID' => 'RAIDID']);
	}
}