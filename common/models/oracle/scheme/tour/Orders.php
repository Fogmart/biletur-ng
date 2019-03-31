<?php

namespace common\models\scheme\tour;

use common\components\BileturActiveRecord;
use common\components\helpers\OraHelper;
use common\interfaces\InvalidateModels;
use common\models\scheme\sns\AppMessages;
use common\models\scheme\t3\RefItems;
use Yii;


/**
 *
 * @author isakov.v
 *
 * Модель заказов туров
 *
 * Поля таблицы:
 * @property string                                       $ID
 * @property string                                       $UNID
 * @property int                                          $ORDNUM
 * @property string                                       $ORDTYPE
 * @property string                                       $ORDNAME
 * @property string                                       $CUSTID
 * @property string                                       $TOURID
 * @property string                                       $PRICEID
 * @property string                                       $GRPID
 * @property string                                       $TOURTYPE
 * @property string                                       $DATE_BEG
 * @property string                                       $DATE_END
 * @property string                                       $TOPERID
 * @property string                                       $TOPERNAME
 * @property string                                       $DSCNTID
 * @property string                                       $ORDDSCNTSUM
 * @property string                                       $TOTSUM
 * @property string                                       $BUYERTYPE
 * @property string                                       $BUYERID
 * @property string                                       $BUYERNAME
 * @property int                                          $ISGRNTLTR
 * @property string                                       $GLPAYDATE
 * @property string                                       $PREDOGDATE
 * @property int                                          $MINTOURISTCNT
 * @property int                                          $TOURISTCNT
 * @property string                                       $ESTORDCOST
 * @property string                                       $ADVSUM
 * @property string                                       $DOGTYPEID
 * @property string                                       $DOGDATE
 * @property string                                       $FULPAYDATE
 * @property string                                       $SALEDATE
 * @property string                                       $CNCLDATE
 * @property string                                       $MAINCNTRYCODE
 * @property string                                       $MAINCNTRYID
 * @property string                                       $ORGID
 * @property string                                       $AGENT
 * @property string                                       $AGENTID
 * @property string                                       $PLACEID
 * @property string                                       $PLACECODE
 * @property int                                          $INSRTOTCNT
 * @property int                                          $INSRDEFCNT
 * @property int                                          $AGENTCNT
 * @property string                                       $VALIDATORID
 * @property int                                          $VALIDCNT
 * @property string                                       $VRJCTREASON
 * @property int                                          $ERRCOUNT
 * @property int                                          $ERRBLCKCNT
 * @property string                                       $GTOURNUM
 * @property string                                       $GBLNKNUM
 * @property string                                       $STATUS
 * @property string                                       $D42ID
 * @property string                                       $WHOCRT
 * @property string                                       $WHNCRT
 * @property string                                       $WHOCHNG
 * @property string                                       $WHNCHNG
 * @property string                                       $MARKSTATUS
 * @property string                                       $CURATORID
 * @property string                                       $CMSNAGRMNTID
 * @property string                                       $DOGID
 *
 * @property-read \common\models\scheme\tour\OrdRemark    $remark
 * @property-read \common\models\scheme\t3\RefItems       $tour
 * @property-read \common\models\scheme\tour\OrdTour      $ordTour
 * @property-read \common\models\scheme\tour\OrdPerson[]  $persons
 * @property-read \common\models\scheme\tour\OrdFlags     $flag
 * @property-read \common\models\scheme\sns\AppMessages[] $messages
 *
 */
class Orders extends BileturActiveRecord implements InvalidateModels {
	public $rulesAccepted = 0;

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{TOUR.ORDERS}}';
	}

	/**
	 * Правила валидации полей
	 * @return array
	 */
	public function rules() {
		return [
			[['rulesAccepted'], 'in', 'range' => [1], 'message' => 'Необходимо Ваше солгасие', 'on' => 'finalStep'],
			[['id'], 'string', 'length' => 10, 'on' => 'finalStep'],
		];
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
		return 'ROWNUM';
	}

	public function init() {
		parent::init();
		$this->_genSecretKey();
		$this->ID = OraHelper::getAbzId(self::className());
		$this->ORGID = self::ORGID;
		$this->ORDNUM = OraHelper::getOrdNum();
	}

	private function _genSecretKey() {
		$length = 10;
		$this->UNID = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	public function getRemark() {
		return $this->hasOne(OrdRemark::className(), ['ORDID' => 'ID']);
	}

	public function getTour() {
		return $this->hasOne(RefItems::className(), ['ID' => 'TOURID']);
	}

	public function getOrdTour() {
		return $this->hasOne(OrdTour::className(), ['ID' => 'ID']);
	}

	public function getPersons() {
		return $this->hasMany(OrdPerson::className(), ['ORDID' => 'ID']);
	}

	public function getMessages() {
		return $this->hasMany(AppMessages::className(), ['REFCID' => 'ID'])->where(
			AppMessages::tableName() . '.SYSID = :sysId', [':sysId' => self::SYSID]
		);
	}

	/**
	 * Автовалидация заказа, если все услуги валидны
	 */
	public function autoValidate() {
		switch ($this->ORDTYPE) {
			case self::ORD_TYPE_TOUR:
				$count = OrdPerson::find('ORDID = :ORDID AND REFQUOTID IS NULL', [':ORDID' => $this->ID])->count();
				break;
			default:
				$count = 1;
				break;
		}

		if ($count > 0) {
			return;
		}

		$count = OrdQuots::find("ORDID = :ORDID", [':ORDID' => $this->ID])->count();

		if ($count == 0) {
			return;
		}

		$count = OrdQuots::find("ORDID = :ORDID AND VLDSTATUS <> 'VALIDATED'", [':ORDID' => $this->ID])->count();

		if ($count > 0) {
			return;
		}

		$this->STATUS = 'VALIDATED';
		$this->VALIDATORID = 'www-data';
		$this->VALIDCNT = (int)$this->VALIDCNT + 1;
		$this->save();
	}

	/**
	 * ORM не вкурсе таких запросов т.к. они не попадают под PDO-стандарты. Выполняем нативно.
	 *
	 */
	public function confirmOrder() {
		$sql = "merge into tour.ord_flags f using (select '" . $this->ID . "' as ordid, 7 as flagid from dual) d
			on (f.ordid = d.ordid and f.flagid = d.flagid) when matched then
			update set f.whnupd = sysdate when not matched then
			insert (ordid, flagid, whnupd) values (d.ordid, d.flagid, sysdate)";
		$connection = Yii::$app->getDb();
		$connection->createCommand($sql)->execute();
	}

	public function getFlag() {
		return $this->hasOne(OrdFlags::className(), ['ORDID' => 'ID']);
	}

	const SYSID = '0000000002';
	const ORGID = '0000000001';
	const ORD_TYPE_TICKET = 'TICKET';
	const ORD_TYPE_TRANSFER = 'TRANSFER';
	const ORD_TYPE_HOTEL = 'HOTEL';
	const ORD_TYPE_VISA = 'VISA';
	const ORD_TYPE_OTHER = 'OTHER';
	const ORD_TYPE_TOUR = 'TOUR';
}