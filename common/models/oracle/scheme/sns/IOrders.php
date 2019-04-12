<?php

namespace common\models\oracle\scheme\sns;

use common\components\BileturActiveRecord;
use common\interfaces\InvalidateModels;
use common\models\forms\RegularBooking\BookingInfo;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 *
 * @author isakov.v
 *
 * Модель заказов
 *
 * Поля таблицы:
 * @property string                                         $ID
 * @property string                                         $ORDER_DT      ' => '17.07.15'
 * @property string                                         $ORDTYPE       ' => 'INT'
 * @property string                                         $FIO           ' => 'Бокарев Николай Петрович                     '
 * @property string                                         $EMAIL         ' => 'bokarev.00@gmail.com                              '
 * @property string                                         $PHONE         ' => '+79856465344'
 * @property string                                         $FAX           ' => null
 * @property string                                         $VALID         ' => null
 * @property string                                         $FWDDATE       ' => '10.08.15'
 * @property string                                         $FWDDATEMAX    ' => null
 * @property string                                         $ROUTE         ' => 'МОСКВА - ВЛАДИВОСТОК                                        '
 * @property string                                         $PSNGLIST      ' => ', Бокарев, Николай, Петрович, 20.11.1998, , , , ,  <br>'
 * @property string                                         $DEPCITY       ' => null
 * @property string                                         $DEPAP         ' => null
 * @property string                                         $ARRCITY       ' => null
 * @property string                                         $ARRAP         ' => null
 * @property string                                         $FWDQTY        ' => 1
 * @property string                                         $FWDCLASS      ' => 'Y'
 * @property string                                         $BCKDATE       ' => '26.08.15'
 * @property string                                         $BCKDATEMAX    ' => null
 * @property string                                         $BCKQTY        ' => 1
 * @property string                                         $BCKCLASS      ' => 'Y'
 * @property string                                         $BUYDATE       ' => null
 * @property string                                         $PAYTYPE       ' => null
 * @property string                                         $WHEREBUY      ' => null
 * @property string                                         $OPERATOR      ' => 'SNS            '
 * @property string                                         $DONE_DT       ' => null
 * @property string                                         $EMAIL2        ' => null
 * @property string                                         $REMARKS       ' => 'P'
 * @property string                                         $OFFER         ' => null
 * @property string                                         $TOTCOST       ' => null
 * @property string                                         $WHNCHNG       ' => '17.07.15'
 * @property string                                         $WHOCHNG       ' => null
 * @property string                                         $PAYSUM        ' => null
 * @property string                                         $WHNMAILOFR    ' => null
 * @property string                                         $UNID          ' => '1HRLR3UVMR'
 * @property string                                         $WHNPRFOFR     ' => null
 * @property string                                         $OFRDECISION   ' => null
 * @property string                                         $USRREMARKS    ' => null
 * @property string                                         $ORDNUM        ' => '285149    '
 * @property string                                         $DFWDDATE      ' => 3
 * @property string                                         $DBCKDATE      ' => 3
 * @property string                                         $CITYFR        ' => 'МОСКВА'
 * @property string                                         $CITYTO        ' => 'ВЛАДИВОСТОК'
 * @property string                                         $FWDPNRSYS     ' => null
 * @property string                                         $FWDPNRTL      ' => null
 * @property string                                         $BCKPNRSYS     ' => null
 * @property string                                         $BCKPNRTL      ' => null
 * @property string                                         $PNRLST        ' => null
 * @property string                                         $TKTLST        ' => null
 * @property string                                         $SNGLPNR       ' => '1'
 * @property string                                         $PLACEID       ' => null
 * @property string                                         $STATUS        ' => 'PNR'
 * @property string                                         $MINPNRTL      ' => null
 * @property string                                         $WHNREAD       ' => '17.07.15'
 * @property string                                         $WHOREAD       ' => 'aEfimova'
 * @property string                                         $PRSNID        ' => null
 * @property string                                         $OPWRKBEGDT    ' => '17.07.15'
 * @property string                                         $OPWRKENDDT    ' => null
 * @property string                                         $OPSTFID       ' => '0000047MY8'
 * @property string                                         $OPSTFNAME     ' => 'Ефимова А.В.'
 * @property string                                         $AGWRKBEGDT    ' => null
 * @property string                                         $AGWRKENDDT    ' => null
 * @property string                                         $AGSTFID       ' => null
 * @property string                                         $AGSTFNAME     ' => null
 * @property string                                         $REJECTREASON  ' => null
 * @property string                                         $CUSTCITY      ' => 'Москва'
 * @property string                                         $CNTPNR        ' => null
 * @property string                                         $CNTTKT        ' => null
 * @property string                                         $CURSTFID      ' => '0000047MY8'
 * @property string                                         $CURSTFNAME    ' => 'Ефимова А.В.'
 * @property string                                         $CUSTIPADR     ' => '95.25.110.3'
 * @property string                                         $CUSTLASTUPD   ' => '17.07.15'
 * @property string                                         $STAFFLASTOPN  ' => '17.07.15'
 * @property string                                         $PREFFCOMMFCLTY' => 'M   '
 * @property string                                         $CNTROUTE      ' => null
 * @property string                                         $ROUTELST      ' => null
 * @property string                                         $NEWMSG        ' => '0'
 * @property string                                         $NEEDTOUR      ' => '0'
 * @property string                                         $FAREID        ' => null
 * @property string                                         $TOURINFO      ' => null
 * @property string                                         $ORDCOST       ' => null
 * @property string                                         $RETCOND       ' => null
 * @property string                                         $DLVRSRVCID    ' => null
 * @property string                                         $DLVRADDR      ' => null
 * @property string                                         $DLVRTIME      ' => null
 * @property string                                         $DLVRCOND      ' => null
 * @property string                                         $TOTFARE       ' => null
 * @property string                                         $TOTACTAX      ' => null
 * @property string                                         $TOTAGTAX      ' => null
 * @property string                                         $TOTINSR       ' => null
 * @property string                                         $VALIDPRSNS    ' => null
 * @property string                                         $ALLOWDOCTYPE  ' => null
 * @property string                                         $PAYONLINETL   ' => null
 * @property string                                         $ATTENTION     ' => null
 * @property string                                         $ADL           ' => null
 * @property string                                         $CHD           ' => null
 * @property string                                         $INF           ' => null
 * @property string                                         $IFS           ' => null
 * @property string                                         $YTH           ' => null
 * @property string                                         $PNN           ' => null
 * @property string                                         $AUXSRVCS      ' => null
 * @property string                                         $USR_PNR       ' => null
 * @property string                                         $EXCHTICKNUM   ' => null
 * @property string                                         $AUXSRVCIDS    ' => null
 * @property string                                         $EXTRAWISH
 * @property string                                         $PHP_PASSENGERS
 * @property string                                         $PHP_SERVICES
 *
 *
 * @property-read \common\models\scheme\sns\IOrderMessage[] $messages
 */
class IOrders extends BileturActiveRecord implements InvalidateModels {
	public $rulesAccepted;

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.IORDERS}}';
	}

	/**
	 * Правила валидации полей
	 * @return array
	 */
	public function rules() {
		return [
			[['rulesAccepted'], 'in', 'range' => [1], 'message' => 'Необходимо Ваше солгасие', 'on' => 'finalStep'],
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
	}

	private function _genSecretKey() {
		$length = 10;
		$this->UNID = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	/**
	 * @return mixed
	 */
	public function getMessages() {
		return $this->hasMany(IOrderMessage::className(), ['ORDID' => 'ID'])
			->orderBy('WHNCRT');
	}

	public function checkRead() {

		if ($this->messages == null) {
			return;
		}
		foreach ($this->messages as $message) {
			if ($message->WHNREAD == null) {
				$message->WHNREAD = new Expression('sysdate');
				$message->update();
			}
		}
	}

	/**
	 * Конвертируем старый формат пассажиров в новый
	 */
	public function convertPassengersFormat() {
		$passengersArray = explode('<br>', $this->PSNGLIST);
		$newPassengersArray = [];

		foreach ($passengersArray as $passenger) {
			$passengerArray = explode(',', $passenger);
			if (count($passengerArray) < 2) {
				continue;
			}
			$newPassenger['lastName'] = trim($passengerArray[1]);
			$newPassenger['firstName'] = trim($passengerArray[2]);
			$newPassenger['middleName'] = trim($passengerArray[3]);
			$newPassenger['birthday'] = trim($passengerArray[4]);
			$newPassenger['age'] = trim($passengerArray[5]);
			$newPassenger['docType'] = trim($passengerArray[7]);
			$newPassenger['docSer'] = trim($passengerArray[8]);
			$newPassenger['docNum'] = trim($passengerArray[9]);
			$newPassengersArray[] = $newPassenger;
		}
		$this->PHP_PASSENGERS = serialize($newPassengersArray);
		$this->update(false);
	}

	/**
	 * Конвертируем пассажиров в старый формат
	 */
	public function convertAndSavePassengersOldFormat() {
		$passengers = unserialize($this->PHP_PASSENGERS);
		$passArray = [];
		foreach ($passengers as $index => $passenger) {
			$passArray = $index . '., ' . $passenger['lastName'] . ', ' . $passenger['firstName']
				. ', ' . $passenger['middleName'] . ', ' . $passenger['birthday'] . ', , , , ,';
		}
		$this->PSNGLIST = implode('<br>', $passArray);
		$this->update(false);
	}

	public function getDocTypes() {
		return $this->_docTypes();
	}

	private function _docTypes() {
		return [
			'Гражданский паспорт РФ'   => 'Гражданский паспорт РФ',
			'Свидетельство о рождении' => 'Свидетельство о рождении'
		];
	}

	/**
	 * Письмо клиенту
	 */
	public function sendClientNotification($event) {
		switch ($event) {
			case self::EVENT_CREATE_ORDER:
				$subject = "Ваш заказ на сайте Билетур";
				$body = "Всероссийская сеть БИЛЕТУР \n";
				$body .= "От Вашего имени на сайте был сделан заказ на авиабилет(ы):  \n";
				$body .= "===========================================================  \n";
				$body .= $this->_formatFlightsInfoToNotification();
				$body .= "===========================================================  \n";
				$body .= "Внимание: Данное сообщение означает, что Ваш заказ принят в обработку, но еще не исполнен. Дождитесь ответного предложения по Вашему заказу.  \n";
				$body .= "Посмотреть свой заказ вы можете, перейдя по следующей ссылке:  \n";
				$body .= "   http://biletur.ru/order/index/" . $this->ID . "?key=" . $this->UNID . "  \n";
				$body .= "Если Вы не можете перейти по ссылке из письма, скопируйте ссылку в браузер, и там перейдите по ней.\n";
				$body .= "Внимание!!! Это письмо было создано роботом, и отвечать на него не требуется.\n";
				$body .= "Если Вы не оформляли заказ, просто игнорируйте это письмо. \n";
				break;
			case self::EVENT_OPERATOR_ADD_MESSAGE:
				$subject = "Новое сообщение в заказе на сайте Билетур";
				$body = "Всероссийская сеть БИЛЕТУР \n";
				$body .= "В Вашем заказе на сайте Билетур есть новое сообщение.  \n";
				$body .= "Посмотреть сообщение вы можете, перейдя по следующей ссылке:  \n";
				$body .= "   http://biletur.ru/order/index/" . $this->ID . "?key=" . $this->UNID . "  \n";
				break;
			default:
				return;
				break;
		}

		Yii::$app->bileturMail->send($this->EMAIL,
			[Yii::$app->params['adminEmail'] => Yii::$app->name],
			$subject,
			$body
		);
	}

	/**
	 * Формирование данных о заказе для информационных писем
	 * @return string
	 */
	private function _formatFlightsInfoToNotification() {
		$body = "Номер заказа: " . $this->ORDNUM . "\n";
		$body .= "Маршрут: " . $this->ROUTE . "\n";
		$body .= "Кол-во пассажиров: " . count(unserialize($this->PHP_PASSENGERS)) . "\n";
		$body .= "Дата вылета: " . $this->FWDDATE . ' ' . $this->DFWDDATE . "\n";
		if (null != $this->BCKDATE) {
			$body .= "Дата обратного рейса: " . $this->BCKDATE . ' ' . $this->DBCKDATE . "\n";
		}
		$body .= "Класс: " . BookingInfo::getServiceClassName($this->FWDCLASS) . "\n";
		$body .= "-- Дополнительные условия -- \n";
		$services = unserialize($this->PHP_SERVICES);
		if (false != $services) {
			$auxServices = ArrayHelper::map(IOrdAuxSrvcs::getActive(), 'ID', 'NAME');
			foreach ($services as $index => $service) {
				foreach ($auxServices as $auxService) {
					if ($auxService['ID'] == $service) {
						$body .= $auxService['NAME'];
					}
				}
			}
		}

		$body .= "-- Контактная информация -- \n";
		$body .= "ФИО: " . $this->FIO . " \n";
		$body .= "Email: " . $this->EMAIL . " \n";
		$body .= "Телефон: " . $this->PHONE . " \n";
		$body .= "Город: " . $this->CUSTCITY . " \n";

		return $body;
	}

	/**
	 * Письмо в коллцентр
	 */
	public function sendOperatorNotification($event) {

		switch ($event) {
			case self::EVENT_CREATE_ORDER:
				$subject = "Новый заказ на сайте Билетур";
				$body = "На сайте был сделан заказ на авиабилет(ы): \n";
				$body .= "===========================================================  \n";
				$body .= $this->_formatFlightsInfoToNotification();
				$body .= "===========================================================  \n";
				$body .= "Посмотреть заказ вы можете, перейдя по следующей ссылке: \n";
				$body .= "http://192.168.1.231/internal/i2/orders/prcsiord2.asp?id=" . $this->ID . "\n";
				$body .= "Если Вы не можете перейти по ссылке из письма, скопируйте ссылку в браузер, и там перейдите по ней. \n";
				break;
			case self::EVENT_CLIENT_ADD_MESSAGE:
				$subject = "Клиент оставил сообщение в заказе";
				$body = "Посмотреть заказ вы можете, перейдя по следующей ссылке: \n";
				$body .= "http://192.168.1.231/internal/i2/orders/prcsiord2.asp?id=" . $this->ID . "\n";
				$body .= "Если Вы не можете перейти по ссылке из письма, скопируйте ссылку в браузер, и там перейдите по ней. \n";
				break;
			default:
				return;
				break;
		}

		Yii::$app->bileturMail->send(Yii::$app->params['callCenterEmail'],
			[Yii::$app->params['robotEmail'] => Yii::$app->name],
			$subject,
			$body
		);
	}

	const STATUS_NEW = 'NEW';
	const STATUS_PNR = 'PNR';
	const STATUS_TKD = 'TKD';
	const STATUS_REJ = 'REJ';
	const STATUS_TKT = 'TKT';
	const NEXT_NUM_NAME = 'ORDN0000000001';
	const EVENT_CREATE_ORDER = 0;
	const EVENT_CLIENT_ADD_MESSAGE = 1;
	const EVENT_OPERATOR_ADD_MESSAGE = 1;
}