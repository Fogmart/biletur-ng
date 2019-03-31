<?php

namespace common\models\oracle\scheme\sns;

use common\components\TagDependency;
use common\interfaces\InvalidateModels;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Блоки мест чартера
 *
 * Поля таблицы:
 * @property int                                                $ID
 * @property int                                                $CHRTID
 * @property string                                             $BLOCKTYPE
 * @property string                                             $DPTDATE
 * @property string                                             $DPTTIME
 * @property string                                             $ARVTIME
 * @property string                                             $CARRCODE
 * @property string                                             $FLNUM
 * @property int                                                $MAXQTY
 * @property string                                             $COST
 * @property string                                             $NAME
 * @property string                                             $SRVCLASS
 * @property string                                             $PSNGTYPE
 * @property string                                             $REMARK
 * @property string                                             $MAXPAYDATE
 * @property string                                             $MAXRJCTDATE
 * @property int                                                $ACTIVE
 * @property int                                                $SEDQTY
 * @property string                                             $PLNPAYSUM
 * @property string                                             $FCTPAYSUM
 * @property int                                                $FULLPAYQTY
 * @property int                                                $SALEQTY
 * @property string                                             $SALESUM
 * @property string                                             $BEGSALEDT
 * @property string                                             $MINWHNPNR
 * @property string                                             $MAXWHNPNR
 * @property string                                             $MINWHNTKT
 * @property string                                             $MAXWHNTKT
 * @property string                                             $MAXFIODATE
 * @property string                                             $MAXTKTDATE
 * @property string                                             $DSP_ID
 * @property string                                             $WHOCRT
 * @property string                                             $WHNCRT
 * @property string                                             $WHOUPD
 * @property string                                             $WHNUPD
 * @property int                                                $BLOCKTYPEID
 * @property string                                             $ENDDATE
 * @property string                                             $DESCRIPT
 *
 * @property-read \common\models\scheme\sns\CharterPriceItems[] $priceItems
 */
class CharterBlocks extends ActiveRecord implements InvalidateModels {
	public $minPrice;

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

	public function getPriceItems() {
		return $this->hasOne(CharterPriceItems::className(), ['BLOCKID' => 'ID']);
	}

	/**
	 * Получение минимальной цены для блока
	 */
	public function getMinPrice($persons) {

		switch ($this->BLOCKTYPE) {
			case 'F':
				$sql = "SELECT GET_CHRT_PRICES(" . $this->CHRTID . ", '" . $this->DPTDATE . "', null, '" . $this->SRVCLASS
					. "', '" . $persons . "') s FROM dual";
				break;
			case 'B':
				$sql = "SELECT GET_CHRT_PRICES(" . $this->CHRTID . ", null ,'" . $this->DPTDATE . "', '" . $this->SRVCLASS
					. "', '" . $persons . "') s FROM dual";
				break;
			default:
				throw new Exception('Неизвестный тип блока чартера');
				break;
		}

		$this->minPrice = Yii::$app->memcache->get(md5($sql));

		if (false == $this->minPrice) {
			$connection = Yii::$app->getDb();
			$command = $connection->createCommand($sql);
			$this->minPrice = $command->queryScalar();
			Yii::$app->memcache->set(md5($sql), $this->minPrice, 60 * 60 * 24,
				new TagDependency([
					CharterBlocks::tableName(),
					CharterOrders::tableName(),
					CharterPrices::tableName(),
					CharterPriceItems::tableName()
				])
			);
		}
	}

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CHRT_BLOCKS}}';
	}

	const BLOCK_TYPE_FORWARD = 'F';
	const BLOCK_TYPE_BACK = 'B';
}