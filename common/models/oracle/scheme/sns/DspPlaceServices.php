<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;
use Yii;
use yii\db\ActiveQuery;

/**
 * @author isakov.v
 *
 * Класс сервисов для точек продаж
 *
 * Т.к. в базе нет нормальной структуры для них то я описал всё тут.
 * Модель точки продажи подготавливает необходимые сервисы исходя из данных этого класса,
 * поэтому в случае заведения структур в базе - всё можно будет легко переделать.
 *
 * Фактически сейчас структура эмулируется внутри этого класса с помощью массивов и связей между ними:
 *
 *  - Приватные методы в этом случае выступают в роли справочников
 *  - get-методы обеспечивают доступ к ним
 *  - Константы являются ключами
 *
 *
 * Поля таблицы:
 *
 * @property string $ID
 * @property string $PLACEID
 * @property string $WRKHRS
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOCHNG
 * @property string $WHNCHNG
 * @property string $SERVID
 * @property string                                                 $EMAIL
 *
 * @property-read \common\models\oracle\scheme\sns\DspOrgPhones $phones
 */
class DspPlaceServices extends DspBaseModel {

	//Вообще, все эти структуры должны быть описаны в базе...
	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.PLCSERVICES}}';
	}

	/**
	 * Получение массива дополнительных сервисов для городов
	 * @return array
	 */
	public static function getAdditionalServices() {
		return self::_additionalServiceToCity();
	}

	/**
	 * Связка доп. сервисов с городами
	 *
	 * @return array
	 */
	private static function _additionalServiceToCity() {
		return [
			self::ADDSRV_SALE_SOCIAL_DV      => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID
			],
			self::ADDSRV_SALE_AEROEXPRESS    => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID,
				DspTowns::BEREGOVOY_ID
			],
			self::ADDSRV_CALLCENTER_SERVICE  => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID,
				DspTowns::BEREGOVOY_ID
			],
			self::ADDSRV_FILIALS_IN_AIRPORTS => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID,
				DspTowns::BEREGOVOY_ID
			],
			self::ADDSRV_CORPORATE_SERVICE   => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID,
				DspTowns::BEREGOVOY_ID
			],
			self::ADDSRV_SALE_DISCOUNT       => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID,
				DspTowns::BEREGOVOY_ID
			],
			self::ADDSRV_BOOKING_HOTELS      => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID,
				DspTowns::BEREGOVOY_ID
			],
			self::ADDSRV_VISA_SERVICE        => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID,
				DspTowns::BEREGOVOY_ID
			],
			//Этот сервис должен определятся из основных сервисов, но на сайте это не проверяется и у точек этих городов он дублируется
			/*self::ADDSRV_RAILROADSALE_SERVICE => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID,
				DspTowns::BEREGOVOY_ID
			],*/
			//----------------------------------------------------------------------------------------------------------------------
			self::ADDSRV_PAY_BY_SB_ONLINE    => [
				DspTowns::NAKHODKA_ID,
				DspTowns::B_KAMEN_ID,
				DspTowns::PARTIZANSK_ID,
				DspTowns::FOKINO_ID,
				DspTowns::BEREGOVOY_ID
			],
		];
	}

	/**
	 * Получение данных по дополнительному сервису
	 *
	 * @param $id
	 *
	 * @return array|null
	 */
	public static function getAdditionalServiceData($id) {
		$services = self::_additionalServices();
		if (array_key_exists($id, $services)) {
			return $services[$id];
		}
		else {
			return null;
		}
	}

	/**
	 * Дополнительные сервисы для точек продаж, которых нет в базе даже в виде идентификаторов
	 *
	 * @return array
	 */
	private static function _additionalServices() {
		return [
			self::ADDSRV_SALE_SOCIAL_DV      => [
				'title_ru' => 'Продажа авиабилетов по социальной программе "Дальний Восток"',
				'title_en' => 'Продажа авиабилетов по социальной программе "Дальний Восток"'
			],
			self::ADDSRV_SALE_AEROEXPRESS    => [
				'title_ru' => 'Продажа билетов на АЭРОЭКСПРЕСС',
				'title_en' => 'Продажа билетов на АЭРОЭКСПРЕСС'
			],
			self::ADDSRV_CALLCENTER_SERVICE  => [
				'title_ru' => 'Единая справочная служба ' . Yii::$app->params['callCenterPhone'] . ' (время работы с '
					. Yii::$app->params['callCenterWorkTime'] . ')',
				'title_en' => 'Единая справочная служба ' . Yii::$app->params['callCenterPhone'] . ' (время работы с '
					. Yii::$app->params['callCenterWorkTime'] . ')'
			],
			self::ADDSRV_FILIALS_IN_AIRPORTS => [
				'title_ru' => 'Аэрокассы в аэропортах Владивостока, Хабаровска, Петропавловск-Камчатска',
				'title_en' => 'Аэрокассы в аэропортах Владивостока, Хабаровска, Петропавловск-Камчатска'
			],
			self::ADDSRV_CORPORATE_SERVICE   => [
				'title_ru' => 'Корпоративное обслуживание организаций',
				'title_en' => 'Корпоративное обслуживание организаций'
			],
			self::ADDSRV_SALE_DISCOUNT       => [
				'title_ru' => 'Прием заявок на авиабилеты по проводимым акциям и распродажам авиакомпаниями',
				'title_en' => 'Прием заявок на авиабилеты по проводимым акциям и распродажам авиакомпаниями'
			],
			self::ADDSRV_BOOKING_HOTELS      => [
				'title_ru' => 'Бронирование, оплата гостиниц и отелей по Всему миру',
				'title_en' => 'Бронирование, оплата гостиниц и отелей по Всему миру'
			],
			self::ADDSRV_VISA_SERVICE        => [
				'title_ru' => 'Услуги по оформлению въездных и выездных виз',
				'title_en' => 'Услуги по оформлению въездных и выездных виз'
			],
			self::ADDSRV_SALE_RAILWAY        => [
				'title_ru' => 'Продажа ж/д билетов по России и странам СНГ',
				'title_en' => 'Продажа ж/д билетов по России и странам СНГ'
			],
			self::ADDSRV_PAY_BY_SB_ONLINE    => [
				'title_ru' => 'Оплата через личный кабинет интернет-сервиса «Сбербанк ОнЛ@йн»',
				'title_en' => 'Оплата через личный кабинет интернет-сервиса «Сбербанк ОнЛ@йн»'
			]
		];
	}

	//А этих вообще обделили, даже идентификаторов не выдали :)

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

	/**
	 * Связка с телефонами привязанными к сервису
	 *
	 * @return ActiveQuery
	 */
	public function getPhones() {
		return $this->hasOne(DspOrgPhones::class, ['SRVCTYPEID' => 'SERVID']);
	}

	/**
	 * Получение данных о сервисе
	 *
	 * @return mixed
	 */
	public function getServiceData() {
		return self::getServiceParams($this->SERVID);
	}

	/**
	 * Статический метод
	 * Возвращает параметры по идентификатору сервиса,
	 * нужен для случаев, когда проходить по объекту сервиса неудобно, например во вьюшке филиалов по городу
	 *
	 * @param $id
	 *
	 * @return array|null
	 */
	public static function getServiceParams($id) {
		$services = self::_services();
		if (array_key_exists($id, $services)) {
			return $services[$id];
		}
		else {
			return null;
		}
	}

	/**
	 * Параметры сервисов которые присутствую в базе в виде идентификаторов
	 * @return array
	 */
	private static function _services() {
		return [
			self::AVIASALE_SERVICE     => [
				'image'                     => '/images/avia.png',
				'title_ru'                  => 'Продажа авиабилетов, билетов на самолет',
				'title_en'                  => 'Sale of airline tickets',
				'serviceDescriptionList_ru' => [
					'Выбор оптимального маршрута и тарифа при покупке авиабилета',
					'Бронирование и продажа авиабилетов в любую страну мира на рейсы любых авиакомпаний',
					'Предоставляются все скидки и льготы авиакомпаний',
				],
				'serviceDescriptionList_en' => [
					'Selection of the optimal route and fare',
					'Booking and sale of tickets to anywhere in the world on flights of any airline',
					'Provides all the discounts and benefits airlines',
				],
			],
			self::RAILROADSALE_SERVICE => [
				'image'                     => '/images/rroad.png',
				'title_ru'                  => 'Продажа ж/д билетов, билеты на поезд',
				'title_en'                  => 'Sale of railway tickets',
				'serviceDescriptionList_ru' => [],
				'serviceDescriptionList_en' => [],
			],
			self::TOURSALE_SERVICE     => [
				'image'                     => '/images/tour.png',
				'title_ru'                  => 'Продажа турпутевок, визы, бронирование гостиниц, отелей ',
				'title_en'                  => 'Продажа турпутевок, визы, бронирование гостиниц, отелей ',
				'serviceDescriptionList_ru' => [],
				'serviceDescriptionList_en' => [],
			],
			self::PAPER_SERVICE        => [
				'image'                     => null,
				'title_ru'                  => 'Услуги бизнесцентра',
				'title_en'                  => 'Услуги бизнесцентра',
				'serviceDescriptionList_ru' => [],
				'serviceDescriptionList_en' => [],
			],
			self::DHL_SERVICE          => [
				'image'                     => '/ImgBank/2014/07/DHL.jpg',
				'title_ru'                  => 'Услуги по экспресс – доставке грузов DHL Express',
				'title_en'                  => 'Услуги по экспресс – доставке грузов DHL Express',
				'serviceDescriptionList_ru' => [],
				'serviceDescriptionList_en' => [],
			],
		];
	}

	const AVIASALE_SERVICE = '0000000001';
	const RAILROADSALE_SERVICE = '0000000002';
	const TOURSALE_SERVICE = '0000000003';
	const PAPER_SERVICE = '0000000004';
	const DHL_SERVICE = '0000000006';
	const ADDSRV_SALE_SOCIAL_DV = 1;
	const ADDSRV_SALE_AEROEXPRESS = 2;
	const ADDSRV_CALLCENTER_SERVICE = 3;
	const ADDSRV_FILIALS_IN_AIRPORTS = 4;
	const ADDSRV_CORPORATE_SERVICE = 5;
	const ADDSRV_SALE_DISCOUNT = 6;
	const ADDSRV_BOOKING_HOTELS = 7;
	const ADDSRV_VISA_SERVICE = 8;
	const ADDSRV_SALE_RAILWAY = 9;
	const ADDSRV_PAY_BY_SB_ONLINE = 10;
}