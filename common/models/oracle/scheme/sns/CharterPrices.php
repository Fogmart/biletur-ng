<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Цены для чартеров
 *
 * Поля таблицы:
 * @property int                                                $ID         ' => 163286
 * @property int                                                $CHRTID     ' => 163271
 * @property string                                             $BLOCKTYPE  ' => 'B'
 * @property string                                             $DPTDATE    ' => '16-05-2014 00:00:00'
 * @property string                                             $DPTTIME    ' => '10:15'
 * @property string                                             $ARVTIME    ' => '14:15'
 * @property string                                             $CARRCODE   ' => 'HZ'
 * @property string                                             $FLNUM      ' => '1752'
 * @property int                                                $MAXQTY     ' => 20
 * @property string                                             $COST       ' => 2520
 * @property string                                             $NAME       ' => 'рейс HZ 1752, эконом'
 * @property string                                             $SRVCLASS   ' => 'Y'
 * @property string                                             $PSNGTYPE   ' => null
 * @property string                                             $REMARK     ' => null
 * @property string                                             $MAXPAYDATE ' => '15-05-2014 00:00:00'
 * @property string                                             $MAXRJCTDATE' => '15-05-2014 00:00:00'
 * @property int                                                $ACTIVE     ' => 1
 * @property int                                                $TIMELIMIT  ' => 1
 * @property int                                                $USEDQTY    ' => 0
 * @property int                                                $LMTQTY     ' => 0
 * @property int                                                $CNFRMQTY   ' => 0
 * @property int                                                $FORINTUSE  ' => 0
 * @property string                                             $PLNPAYSUM  ' => null
 * @property string                                             $FCTPAYSUM  ' => null
 * @property string                                             $FULLPAYQTY ' => null
 * @property int                                                $SALEQTY    ' => 0
 * @property string                                             $SALESUM    ' => null
 * @property string                                             $BEGSALEDT  ' => '20-04-2014 21:30:35'
 * @property string                                             $MINWHNPNR  ' => null
 * @property string                                             $MAXWHNPNR  ' => null
 * @property string                                             $MINWHNTKT  ' => null
 * @property string                                             $MAXWHNTKT  ' => null
 * @property string                                             $MAXFIODATE ' => '15-05-2014 00:00:00'
 * @property string                                             $MAXTKTDATE ' => '15-05-2014 00:00:00'
 * @property string                                             $DSP_ID     ' => null
 * @property string                                             $WHOCRT     ' => 'atsymbal'
 * @property string                                             $WHNCRT     ' => '03-04-2014 15:20:58'
 * @property string                                             $WHOUPD     ' => 'ashvedova'
 * @property string                                             $WHNUPD     ' => '20-04-2014 21:32:38'
 * @property int                                                $BLOCKTYPEID' => 2
 * @property string                                             $ENDDATE    ' => null
 * @property string                                             $DESCRIPT   ' => null
 *
 * @property-read                                               $charterBlockType
 * @property-read   \common\models\scheme\sns\CharterPriceItems $priceItem
 */
class CharterPrices extends ActiveRecord implements InvalidateModels {
	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CHRT_PRICES}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 10;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}

	public function getPriceItem() {
		return $this->hasOne(CharterPriceItems::className(), ['PRICEID' => 'ID']);
	}
}