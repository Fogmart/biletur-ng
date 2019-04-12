<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;
use Yii;
use yii\db\ActiveQuery;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы PLACES
 *
 * Поля таблицы:
 * @property string                  $ID
 * @property string                  $SALEPLACE
 * @property string                  $FILIAL
 * @property string                  $CITY
 * @property string                  $NAME
 * @property int                     $ACTIVE
 * @property string                  $LOCID
 * @property string                  $LOCNAME
 * @property string                  $CITYID
 * @property string                  $TKP_CODE
 * @property int                     $BP_QTY
 * @property int                     $NDSIRP_QTY
 * @property int                     $EORDER
 * @property int                     $SALEAVIA
 * @property int                     $SALERROAD
 * @property int                     $SALETOUR
 * @property int                     $SALEPAPER
 * @property int                     $RANG
 * @property string                  $DESCR_URL
 * @property string                  $FILIALID
 * @property string                  $CRCARDS
 * @property string                  $PLACEMENT
 * @property string                  $ROUTEINFO
 * @property string                  $D_IMGMAPURL
 * @property string                  $ICNMAPURL
 * @property int                     $FILIAL_HQ
 * @property string                  $D_ADDRESS
 * @property string                  $ID1C
 * @property string                  $PCC
 * @property string                  $ZIP
 * @property int                     $HIDEFRMWEB
 * @property string                  $OFCMNGRID
 * @property int                     $SALEIATA
 * @property string                  $RRSUPORGID
 * @property string                  $NN_VLDTR
 * @property string                  $EMAIL
 * @property int                     $IDAURA
 * @property string                  $VALIDR_SU
 * @property int                     $IDAURALST
 * @property string                  $AMADEUSOFCID
 * @property int                     $SALESPUTNIK
 * @property string                  $GOOGLEMAP
 * @property string                  $WHOCRT
 * @property string                  $WHNCRT
 * @property string                  $WHOUPD
 * @property string                  $WHNUPD
 *
 * Свзяки:
 * @property-read DspPlaceServices[] $services
 * @property-read DspTowns           $town
 * @property-read DspFilials         $filial
 * @property-read DspOrgPhones[]     $phonesToSite
 * @property-read DspOrgStaff        $manager
 *
 */
class DspPlaces extends DspBaseModel {
	const ATTR_ID = 'ID';
	const ATTR_SALEPLACE = 'SALEPLACE';
	const ATTR_NAME = 'NAME';
	const ATTR_FILIALID = 'FILIALID';
	const ATTR_CITYID = 'CITYID';
	const ATTR_CITY = 'CITY';
	const ATTR_ACTIVE = 'ACTIVE';
	const ATTR_LOCID = 'LOCID';
	const ATTR_LOCNAME = 'LOCNAME';
	const ATTR_HIDEFRMWEB = 'HIDEFRMWEB';
	const ATTR_OFCMNGRID = 'OFCMNGRID';
	const ATTR_EMAIL = 'EMAIL';
	const ATTR_RANG = 'RANG';
	const ATTR_FILIAL_HQ = 'FILIAL_HQ';
	const ATTR_BP_QTY = 'BP_QTY';
	const ATTR_NDSIRP_QTY = 'NDSIRP_QTY';
	const ATTR_DESCR_URL = 'DESCR_URL';
	const ATTR_CRCARDS = 'CRCARDS';
	const ATTR_PLACEMENT = 'PLACEMENT';
	const ATTR_ROUTEINFO = 'ROUTEINFO';
	const ATTR_EORDER = 'EORDER';
	const ATTR_SALEIATA = 'SALEIATA';
	const ATTR_WHOCRT = 'WHOCRT';
	const ATTR_WHNCRT = 'WHNCRT';
	const ATTR_WHOUPD = 'WHOUPD';
	const ATTR_WHNUPD = 'WHNUPD';
	const ATTR_ID1C = 'ID1C';
	const ATTR_D_FILIAL = 'D_FILIAL';
	const ATTR_IDAURA = 'IDAURA';
	const ATTR_IDAURALST = 'IDAURALST';
	const ATTR_D_TKP_CODE = 'D_TKP_CODE';
	const ATTR_D_RRSUPORGID = 'D_RRSUPORGID';
	const ATTR_D_NN_VLDTR = 'D_NN_VLDTR';
	const ATTR_D_VALIDR_SU = 'D_VALIDR_SU';
	const ATTR_PCC = 'PCC';
	const ATTR_AMADEUSOFCID = 'AMADEUSOFCID';
	const ATTR_D_SALEAVIA = 'D_SALEAVIA';
	const ATTR_D_SALERROAD = 'D_SALERROAD';
	const ATTR_D_SALETOUR = 'D_SALETOUR';
	const ATTR_D_SALEPAPER = 'D_SALEPAPER';
	const ATTR_D_SALESPUTNIK = 'D_SALESPUTNIK';
	const ATTR_D_IMGMAPURL = 'D_IMGMAPURL';
	const ATTR_D_ICNMAPURL = 'D_ICNMAPURL';
	const ATTR_D_ADDRESS = 'D_ADDRESS';
	const ATTR_D_ZIP = 'D_ZIP';
	const ATTR_D_GOOGLEMAP = 'D_GOOGLEMAP';
	const ATTR_WEBNAME = 'WEBNAME';

	public $aviaSaleService = false;
	public $railRoadSaleService = false;
	public $tourSaleService = false;
	public $title;
	public $listServices;
	public $creditCards;

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.PLACES}}';
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
		return 'WHNUPD';
	}

	/**
	 * Получение города
	 * @return ActiveQuery
	 */
	public function getTown() {
		return $this->hasOne(DspTowns::class, ['ID' => 'CITYID']);
	}

	/**
	 * Получение сервисов точки
	 * @return ActiveQuery
	 */
	public function getServices() {
		return $this->hasMany(DspPlaceServices::class, ['PLACEID' => 'ID'])
			->orderBy('SERVID');
	}

	/**
	 * Получение филиала к которому относится точка
	 * @return ActiveQuery
	 */
	public function getFilial() {
		return $this->hasOne(DspFilials::class, ['ID' => 'FILIALID']);
	}

	/**
	 * Получение телефонов для сайта
	 * @return ActiveQuery
	 */
	public function getPhonesToSite() {
		return $this->hasMany(DspOrgPhones::class, ['PLACEID' => 'ID'])->select(
			"
					max(ID) ID ,max(ORGID) ORGID,max(STAFFID) STAFFID, max(DEPID) DEPID,max(PLACEID) PLACEID,max(PHONETYPE) PHONETYPE,max(CNTRYPCOD) CNTRYPCOD,max(CITYPCODE) CITYPCODE,
			max(PHONENUM) PHONENUM,max(PHONEUSE) PHONEUSE,max(WHOCRT) WHOCRT,max(WHNCRT) WHNCRT,max(WHOCHNG) WHOCHNG,max(WHNCHNG) WHNCHNG
			,max(HIDEINWEB) HIDEINWEB,max(LOCID) LOCID,max(BEGDATE) BEGDATE,max(ENDDATE) ENDDATE,max(SRVCTYPEID) SRVCTYPEID
					"
		)
			->where(
				"SRVCTYPEID = '" . DspPlaceServices::AVIASALE_SERVICE . "'
						AND PHONETYPE IN ('C', 'S', 'F')
						AND nvl(HIDEINWEB, 0) = 0"
			)
			->groupBy("PHONENUM");
	}

	/**
	 * Получение директора точки продажи
	 * @return ActiveQuery
	 */
	public function getManager() {
		return $this->hasOne(DspOrgStaff::class, ['ID' => 'OFCMNGRID'])
			->where(
				'ACTIVE = 1'
			);
	}

	/**
	 * Подготавливаем данные для вьюшки
	 */
	public function prepareData() {
		$this->_prepareCreditCards();
		$this->_prepareServices();
		$this->_prepareListServices();
		$this->_prepareTitle();
	}

	/**
	 * Подготовка кредитных карт
	 */
	private function _prepareCreditCards() {
		//Подготовим кредитные карты
		if ($this->CRCARDS != null && $this->CRCARDS != '') {
			$creditCards = trim($this->CRCARDS);
			$creditCards = explode(',', $creditCards);
			foreach ($creditCards as $creditCardCode) {
				$this->creditCards[$creditCardCode] = [
					'title' => self::_getCreditCardName($creditCardCode),
					'image' => self::_getCreditCardImageUrl($creditCardCode)
				];
			}
		}
	}

	/**
	 * Получим полное название кредитной карты по её коду
	 *
	 * @param $card
	 *
	 * @return mixed
	 */
	private static function _getCreditCardName($card) {
		$cardArray = self::_creditCardNames();
		if (array_key_exists($card, $cardArray)) {
			return $cardArray[$card];
		}
		else {
			return $card;
		}
	}

	/**
	 * Названия кредитных карт
	 * @return array
	 */
	private static function _creditCardNames() {
		return [
			'VI' => 'Visa',
			'CA' => 'Master Card',
			'DC' => 'Diners Club',
			'AX' => 'American Express',
			'MA' => 'Maestro',
			'ЗК' => 'Золотая корона',
			'JB' => 'JCB Card',
		];
	}

	/**
	 * Получим URL изображения логотипа кредитной карты для её кода
	 *
	 * @param $card
	 *
	 * @return string
	 */
	private static function _getCreditCardImageUrl($card) {
		$imageArray = self::_creditCardImages();
		if (array_key_exists($card, $imageArray)) {
			return $imageArray[$card];
		}
		else {
			return '';
		}
	}

	/**
	 * URL изображения логотипа кредитной карты по её коду
	 *
	 * @return array
	 */
	private static function _creditCardImages() {
		return [
			'VI' => '/imgbank/logos/visa.gif',
			'CA' => '/imgbank/logos/master.gif',
			'DC' => '/imgbank/logos/dinersclub.gif',
			'AX' => '/imgbank/logos/americanexpress.gif',
			'MA' => '/imgbank/logos/maestro.gif',
			'ЗК' => '/imgbank/logos/goldcrown.gif',
			'JB' => '/imgbank/logos/jcb.jpg',
		];
	}

	/**
	 * Подготовка сервисов из базы
	 */
	private function _prepareServices() {
		foreach ($this->services as $service) {
			if ($service->SERVID == DspPlaceServices::AVIASALE_SERVICE) {
				$this->aviaSaleService = true;
			}
			if ($service->SERVID == DspPlaceServices::RAILROADSALE_SERVICE) {
				$this->railRoadSaleService = true;
			}
			if ($service->SERVID == DspPlaceServices::TOURSALE_SERVICE) {
				$this->tourSaleService = true;
			}
		}
	}

	/**
	 * Подготовка расширенных сервисов филиала, которых нет в базе
	 */
	private function _prepareListServices() {
		$lang = Yii::$app->env->getLanguage();
		$this->listServices = [];
		//Пройдём по сервисам из базы и возьмём их описания захардкоженые в модели DspPlaceServices
		$aviaSalePhone = [];
		foreach ($this->services as $service) {
			$baseService = $service->getServiceData();
			if (null === $baseService) {
				continue;
			}
			$this->listServices['base'][$service->SERVID] = [
				'image' => $baseService['image'],
				'title' => $baseService['title_' . $lang]
			];
			if (count($baseService['serviceDescriptionList_' . $lang]) > 0) {
				$desc = [];
				foreach ($baseService['serviceDescriptionList_' . $lang] as $serviceDescription) {
					$desc[] = $serviceDescription;
				}
				$this->listServices['base'][$service->SERVID]['additionalDescription'] = $desc;
			}

			$this->listServices['base'][$service->SERVID]['workTime'] = trim($service->WRKHRS);
			$servicePhones = [];
			foreach ($this->phonesToSite as $phone) {
				if ($phone->SRVCTYPEID == $service->SERVID) {
					$servicePhones[] = [
						'code'   => trim($phone->CITYPCODE),
						'number' => trim($phone->PHONENUM)
					];
					if ($service->SERVID == DspPlaceServices::AVIASALE_SERVICE) {
						$aviaSalePhone = $servicePhones;
					}
				}
			}

			//Если телефонов для сервиса нет то добавим авиа телефоны
			if (count($servicePhones) > 0) {
				$this->listServices['base'][$service->SERVID]['phone'] = $servicePhones;
			}
			else {
				$this->listServices['base'][$service->SERVID]['phone'] = $aviaSalePhone;
			}

			//Если не было и авиателефонов то ставим телефон коллцентра
			if (count($this->listServices['base'][$service->SERVID]['phone']) == 0) {
				$this->listServices['base'][$service->SERVID]['phone'][] = [
					'title'  => 'Единая справочная служба',
					'number' => Yii::$app->params['callCenterPhone']
				];
			}
		}
		//Теперь добавим сервисы, определенные для городов
		$additionalServices = DspPlaceServices::getAdditionalServices();
		foreach ($additionalServices as $addService => $towns) {
			if (false !== array_search($this->CITYID, $towns)) {
				$this->listServices['extended'][] = DspPlaceServices::getAdditionalServiceData($addService)['title_' . $lang];
			}
		}
	}

	/**
	 * Формирование заголовка точки продажи
	 */
	private function _prepareTitle() {
		$this->title = 'Продажа и заказ авиабилетов';
		if (true === $this->railRoadSaleService) {
			$this->title .= ', ж/д билетов';
		}
		if (true === $this->tourSaleService) {
			$this->title .= ', туристических путевок';
		}
		if (true === $this->aviaSaleService) {
			$this->title .= ', авиакассы';
		}
	}
}