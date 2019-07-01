<?php

namespace common\models\oracle\scheme\t3;

use common\components\helpers\LArray;
use common\base\helpers\LString;
use common\models\forms\Common\ContactInfo;
use common\models\forms\TourBooking\OrderInfoForm;
use common\models\forms\TourBooking\RefQuotsForm;
use common\models\oracle\scheme\DspBaseModel;
use common\models\oracle\scheme\sns\Countries;
use common\models\oracle\scheme\sns\OrgStaff;
use common\models\procedures\TourCreateOrderCustomerInfo;
use common\models\procedures\TourUpdOrdQuot;
use common\models\oracle\scheme\t3\queries\QueryRefItems;
use common\models\scheme\tour\Orders;
use common\models\scheme\tour\OrdPerson;
use common\models\scheme\tour\OrdRemark;
use common\models\scheme\tour\OrdTour;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * @author isakov.v
 *
 * Модель туров из схемы T3
 *
 * Поля таблицы:
 *
 * @property int                                                   $ID
 * @property int                                                   $PARID
 * @property string                                                $ORGID
 * @property string                                                $REFQUOTID
 * @property int                                                   $ITMTYPEID
 * @property string                                                $NAME
 * @property string                                                $PLNBEGDATE
 * @property string                                                $PLNENDDATE
 * @property string                                                $BEGDATE
 * @property string                                                $ENDDATE
 * @property int                                                   $INBUNDLE
 * @property string                                                $SPEC_INFO
 * @property int                                                   $ACTIVE
 * @property int                                                   $ISPRICEVALID
 * @property int                                                   $REQ_BLNK
 * @property int                                                   $REQ_BLNKTYPEID
 * @property string                                                $WHOCRT
 * @property string                                                $WHNCRT
 * @property string                                                $WHOUPD
 * @property string                                                $WHNUPD
 * @property int                                                   $CID
 * @property string                                                $VALIDATORID
 * @property string                                                $WHNVALID
 * @property int                                                   $DAYQTY
 * @property int                                                   $DQ_FIXED
 * @property int                                                   $PRSNINPRICE
 * @property int                                                   $PRICEPER_TU
 * @property int                                                   $TU_LEN
 * @property int                                                   $SHWPRIORITY
 * @property int                                                   $ISDEPENDANT
 * @property int                                                   $LANGID
 * @property int                                                   $PERIODCALCTYPE
 * @property int                                                   $ALLOWPRSNDOCTYPES
 *
 * @property-read \common\models\oracle\scheme\t3\RiTour           $description
 * @property-read \common\models\oracle\scheme\t3\RiTourWps[]      $wps
 * @property-read \common\models\oracle\scheme\t3\RiTourActivity[] $activity
 * @property-read \common\models\oracle\scheme\t3\RiLaps[]         $laps
 * @property-read \common\models\oracle\scheme\t3\RiLaps[]         $activeLaps
 * @property-read \common\models\oracle\scheme\t3\RiAd             $riAd
 * @property-read \common\models\oracle\scheme\t3\RefQuots[]       $activeQuots
 * @property-read \common\models\oracle\scheme\t3\RefQuots[]       $quots
 * @property-read \common\models\oracle\scheme\sns\DspOrgStaff     $staff
 *
 */
class RefItems extends DspBaseModel {

	const ATTR_ID = 'ID';

	/** @var  common\models\oracle\scheme\sns\DspCountries $mainCountry */
	public $mainCountry;

	/** @var  string $route */
	public $route;

	/** @var \common\models\oracle\scheme\t3\RiTourWps $wpBeg */
	public $wpBeg;

	/** @var  \common\models\oracle\scheme\t3\RiTourWps $wpEnd */
	public $wpEnd;

	/**
	 * @return QueryRefItems|ActiveQuery
	 */
	public static function find() {
		return new QueryRefItems(get_called_class());
	}

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.REFITEMS}}';
	}

	public function attributeLabels() {
		return ['ID' => ''];
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

	/**
	 * Описание тура
	 * @return ActiveQuery
	 */
	public function getDescription() {
		return $this->hasOne(RITour::class, ['ITMID' => 'ID']);
	}

	/**
	 * Маршрут тура
	 * @return ActiveQuery
	 */
	public function getWps() {
		return $this->hasMany(RITourWps::class, ['ITMID' => 'ID'])->orderBy([RITourWps::ATTR_NUMBER => SORT_ASC]);
	}
	const REL_WPS = 'wps';

	/**
	 * Только активные услуги тура
	 *
	 * @return mixed
	 */
	public function getActiveQuots() {
		return $this->hasMany(RefQuots::class, ['ITMID' => 'ID'])
			->where(
				RefQuots::tableName()
				. '.ENDDATE > sysdate AND TOTSUM IS NOT NULL AND ISVALID = 1 AND T3.REFQUOTS.ID IN (SELECT QUOTID FROM T3.RQ_PRCLST WHERE PRCLSTID = 1)'
			)
			->orderBy(RefQuots::tableName() . '.ENDDATE');
	}
	const REL_ACTIVE_QUOTS = 'activeQuots';

	/**
	 * Все услуги, привязанные к туру, без каких-либо фильтров
	 *
	 * @return mixed
	 */
	public function getQuots() {
		return $this->hasMany(RefQuots::class, ['ITMID' => 'ID']);
	}
	const REL_QUOTS = 'quots';

	/**
	 * Этапы тура
	 * @return mixed
	 */
	public function getActivity() {
		return $this->hasMany(RITourActivity::class, ['ITMID' => 'ID'])
			->orderBy(RITourActivity::tableName() . '.NPP');
	}

	/**
	 * Заезды тура
	 *
	 * @return mixed
	 */
	public function getLaps() {
		return $this->hasMany(RILaps::class, ['ITMID' => 'ID'])
			->orderBy('BEGDATE');
	}
	const ATTR_LAPS = 'laps';

	/**
	 * Активные заезды тура
	 *
	 * @return mixed
	 */
	public function getActiveLaps() {
		return $this->hasMany(RILaps::class, ['ITMID' => 'ID'])
			->where(RILaps::tableName() . '.BEGDATE > sysdate')
			->orderBy('BEGDATE');
	}
	const ATTR_ACTIVE_LAPS = 'activeLaps';

	/**
	 * Отбор тура по зоне публикации, используем с joinWith для всех зависимых переменных
	 *
	 * @return mixed
	 */
	public function getActive() {
		return $this->hasMany(RIAd::class, ['ITMID' => 'ID'])
			->where(
				"nvl(T3.RI_AD.BEGDATE, trunc(sysdate)) <= trunc(sysdate) AND nvl(T3.RI_AD.ENDDATE, trunc(sysdate)) >= trunc(sysdate)
										AND TOURZONEID = " . Yii::$app->env->getTourZone()
			);
	}
	const REL_ACTIVE = 'active';

	/**
	 * Связь с типами тура через таблицу T3.RI_TOUR_TYPES
	 *
	 * @return mixed
	 */
	public function getTypes() {
		return $this->hasMany(TourTypes::className(), ['ID' => 'TOURTYPEID'])
			->viaTable(
				'T3.RI_TOUR_TYPES', ['ITMID' => 'ID']
			);
	}

	/**
	 * Связь с сотрудниками, привязанными к туру, сделал 1=1(hasOne) т.к. не понял может ли быть 1=n(hasMany)
	 * В принципе таблица вроде бы позволяет, но я не стал искать. Если будет надо то просто поменяйте тип связи.
	 *
	 * @return mixed
	 */
	public function getStaff() {
		return $this->hasOne(OrgStaff::className(), ['ID' => 'STAFFID'])
			->viaTable(
				RIStaff::tableName(), ['ITMID' => 'ID']
			);
	}

	/**
	 * Формируем строку "Цена от до" для вьюшки каталога
	 * @return array
	 */
	public function quotsSummMinMax() {
		if ($this->activeQuots === null) {
			return null;
		}

		$summs = [];

		foreach ($this->activeQuots as $quot) {
			if ($quot->CRNCY != 'RUB') {
				$summs[] = $quot->getTotRubSumm();
			}
			else {
				$summs[] = $quot->TOTSUM;
			}
		}
		if (count($summs) == 0) {
			return null;
		}

		$summs = array_unique($summs);

		sort($summs);

		if (count($summs) == 1) {
			return [LString::formatMoney($summs[0])];
		}

		$minSum = $summs[0];
		$maxSum = $summs[count($summs) - 1];

		return [LString::formatMoney($minSum), LString::formatMoney($maxSum)];
	}

	/**
	 * @param ContactInfo   $contactInfo
	 * @param RefQuotsForm  $refQuotForm
	 * @param OrderInfoForm $orderInfo
	 *
	 * @return array;
	 */
	public function createOrder(ContactInfo $contactInfo, RefQuotsForm $refQuotForm, OrderInfoForm $orderInfo) {
		$this->_setMainCountry();
		$this->_setRoute();
		$this->_setBegEndWayPoints();


		$totalSum = 0;
		//Посчитаем итоговую сумму по услугам.
		foreach ($refQuotForm as $refQuotId => $count) {
			/** @var \common\models\scheme\t3\RefQuots $refQuot */
			$refQuot = RefQuots::find($refQuotId)->one();
			if (null == $refQuot) {
				continue;
			}
			$totalSum = (int)$totalSum + ((int)$refQuot->getTotRubSumm() * (int)$count);
		}

		$order = new Orders();
		$order->ORDTYPE = Orders::ORD_TYPE_TOUR;
		$order->TOURID = trim($this->ID);
		$order->ORDNAME = trim($this->NAME);
		$order->TOPERID = trim($this->description->tourOperator->ID);
		$order->TOPERNAME = trim(substr($this->description->tourOperator->NAME, 0, 50));
		$order->MAINCNTRYCODE = trim($this->mainCountry->CODE);
		$order->MAINCNTRYID = trim($this->mainCountry->ID);
		$order->BUYERNAME = trim(
			$contactInfo->lastName . ' ' . $contactInfo->firstName . ' ' . $contactInfo->middleName
		);
		$order->DATE_BEG = trim($orderInfo->begDate);
		$order->DATE_END = trim($orderInfo->endDate);
		$order->MINTOURISTCNT = 0;
		$order->ADVSUM = 0;
		$order->TOTSUM = 0;
		$order->VALIDCNT = 0;
		$order->insert();

		$ordTour = new OrdTour();
		$ordTour->ID = trim($order->ID);
		$ordTour->TOURID = trim($order->TOURID);
		$ordTour->RAIDID = trim($orderInfo->lapId);
		$ordTour->TOURNAME = trim($order->ORDNAME);
		$ordTour->TOPERID = trim($order->TOPERID);
		$ordTour->TOPERNAME = trim($order->TOPERNAME);
		$ordTour->MAINCNTRYID = trim($order->MAINCNTRYID);
		$ordTour->MAINCNTRYCODE = trim($order->MAINCNTRYCODE);
		$ordTour->DATE_BEG = trim($order->DATE_BEG);
		$ordTour->DATE_END = trim($order->DATE_END);
		$ordTour->ROUTE = trim($this->route);
		$ordTour->WP_BEG = trim($this->wpBeg->CITY);
		$ordTour->MEETTIME = trim($this->wpBeg->MEETTIME);
		$ordTour->WP_END = trim($this->wpEnd->CITY);
		$ordTour->MINTOURISTCNT = 0;
		$ordTour->ESTTOURPRICE = 0;
		$ordTour->ADVSUM = 0;
		$ordTour->WHNCRT = new Expression('sysdate');
		$ordTour->WHNCHNG = new Expression('sysdate');
		$ordTour->WHOCRT = 'www-data';
		$ordTour->WHOCHNG = 'www-data';
		$ordTour->ESTTOURPRICE = $totalSum;
		$ordTour->insert();

		$ordRemark = new OrdRemark();
		$ordRemark->ORDID = trim($order->ID);
		$ordRemark->REMARK = 'Адрес электронной почты покупателя: ' . $contactInfo->email;
		$ordRemark->insert();

		$procedure = new TourCreateOrderCustomerInfo();
		$procedure->params = [
			':P_LNAME'  => $contactInfo->lastName,
			':P_FNAME'  => $contactInfo->firstName,
			':P_MNAME'  => $contactInfo->middleName,
			':P_EMAIL'  => $contactInfo->email,
			':P_PHONE'  => $contactInfo->phone,
			':P_CITY'   => $contactInfo->city,
			':P_ORDID'  => $order->ID,
			':P_ORDSUM' => $totalSum,
			':P_WIWID'  => '',
		];

		$procedure->call();

		$whoIsWhoId = $procedure->getResult();

		//Добавляем первого туриста без услуги, с данными из формы контактов, сделал его привязанным к первой услуге
		$ordPerson = new OrdPerson();
		$ordPerson->ORDID = trim($order->ID);
		$ordPerson->WIWID = $whoIsWhoId;
		$ordPerson->LNAME = $contactInfo->lastName;
		$ordPerson->FNAME = $contactInfo->firstName;
		$ordPerson->MNAME = $contactInfo->middleName;
		$ordPerson->PRSNSUM = 0;
		$ordPerson->PHONE = $contactInfo->phone;
		$ordPerson->WHNCRT = new Expression('sysdate');
		$ordPerson->WHNCHNG = new Expression('sysdate');
		$ordPerson->WHOCRT = 'www-data';
		$ordPerson->WHOCHNG = 'www-data';
		$ordPerson->save();

		//Добавим, выбранные НЕиндивидуальные услуги на первого пассажира
		if (null !== $refQuotForm->refQuots && null !== $refQuotForm->allowedNotIndividualQuots) {
			$allowQuots = LArray::extract($refQuotForm->allowedNotIndividualQuots, 'ID');
			foreach ($refQuotForm->refQuots as $refQuotId => $count) {
				if (false !== array_search($refQuotId, $allowQuots)) {
					for ($i = 0; $i < $count; $i++) {
						$procedure = new TourUpdOrdQuot();
						$procedure->params = [
							':P_REQUOTID'  => $refQuotId,
							':P_ORDID'     => $order->ID,
							':P_STAFFID'   => '',
							':P_PRSNID'    => $ordPerson->ID,
							':P_PDQTY'     => $count,
							':P_ORDQUOTID' => '',
						];
						$procedure->call();
					}
				}
			}
		}

		//Добавим туристов для выбранных индивидуальных услуг, если у тура вообще они есть
		if (null !== $refQuotForm->refQuots && null !== $refQuotForm->allowedIndividualQuots) {
			$allowQuots = LArray::extract($refQuotForm->allowedIndividualQuots, 'ID');
			foreach ($refQuotForm->refQuots as $refQuotId => $count) {
				if (false !== array_search($refQuotId, $allowQuots)) {
					for ($i = 0; $i < $count; $i++) {
						$ordPerson = new OrdPerson();
						$ordPerson->ORDID = trim($order->ID);
						$ordPerson->REFQUOTID = trim($refQuotId);
						$ordPerson->LNAME = '-';
						$ordPerson->FNAME = '-';
						$ordPerson->MNAME = '-';
						$ordPerson->PRSNSUM = 0;
						$ordPerson->WHNCRT = new Expression('sysdate');
						$ordPerson->WHNCHNG = new Expression('sysdate');
						$ordPerson->WHOCRT = 'www-data';
						$ordPerson->WHOCHNG = 'www-data';
						$ordPerson->save();

						$procedure = new TourUpdOrdQuot();
						$procedure->params = [
							':P_REQUOTID'  => $refQuotId,
							':P_ORDID'     => $order->ID,
							':P_STAFFID'   => '',
							':P_PRSNID'    => $ordPerson->ID,
							':P_PDQTY'     => $count,
							':P_ORDQUOTID' => '',
						];
						$procedure->call();
					}

				}
			}
		}

		Yii::$app->memcache->set(md5('order-info-message-' . $order->ID), 1, 60 * 60 * 5);

		$order->autoValidate();

		return ['ordId' => $order->ID, 'key' => $order->UNID];
	}

	/**
	 * Определение основной страны тура
	 * Хм. У RITour есть поле $MAINCNTRYID - может его использвать правильнее?
	 */
	private function _setMainCountry() {
		if (null == $this->wps) {
			$this->mainCountry = new Countries();

			return;
		}
		$countries = [];
		foreach ($this->wps as $wayPoint) {
			$countries[$wayPoint->NDAYS] = $wayPoint->city->country;
		}
		$maxDays = max(array_keys($countries));
		$this->mainCountry = $countries[$maxDays];
	}

	/**
	 * Определение маршрута
	 */
	private function _setRoute() {
		if (null == $this->wps) {
			$this->route = null;

			return;
		}

		$countries = [];

		foreach ($this->wps as $wayPoint) {
			$countries[$wayPoint->NPP] = $wayPoint->city->country->NAME;
		}

		ksort($countries);

		$prevCountry = null;
		foreach ($countries as $country) {
			if ($prevCountry != $country) {
				$this->route[] = $country;
				$prevCountry = $country;
			}
		}
		$this->route = implode(' -> ', $this->route);
	}

	/**
	 * Установка конечного и начального пунктов путешествия
	 */
	private function _setBegEndWayPoints() {
		if (null == $this->wps) {
			$this->wpBeg = new RITourWps();
			$this->wpEnd = new RITourWps();

			return;
		}

		$wayPointsArray = [];
		foreach ($this->wps as $wayPoint) {
			$wayPointsArray[$wayPoint->NPP] = $wayPoint;
		}

		$maxNpp = max(array_keys($wayPointsArray));
		$minNpp = min(array_keys($wayPointsArray));

		$this->wpBeg = $wayPointsArray[$maxNpp];
		$this->wpEnd = $wayPointsArray[$minNpp];
	}
}