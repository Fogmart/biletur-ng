<?php

namespace common\components;

use common\models\forms\CharterBooking\CharterOrderForm;
use Yii;
use yii\base\Model;

/**
 *
 * Базовая форма для моделей поддерживающих мультиязычность и историю поиска
 * В идеале все формы должны наследоваться от этой
 *
 * @author isakov.v
 */
class BileturModel extends Model {

	/** @var UserRequestsHistory $historyRequest */
	public $historyRequest;

	public function init() {
		parent::init();
		$this->historyRequest = new UserRequestsHistory();
	}

	/**
	 * Переопределяем метод для получения лэйблов в зависимости от языка окружения
	 * @return mixed
	 */
	public function attributeLabels() {
		return $this->_getLabels();
	}

	/**
	 * Получение значений для вызывающего класса
	 *
	 * @return mixed
	 */
	private function _getLabels() {
		$labels = self::_labels();

		return $labels[get_called_class()][Yii::$app->env->getLanguage()];
	}

	/**
	 * Массив значений
	 *
	 * @return array
	 */
	private function _labels() {
		return [
			//Форма поиска рейсов
			\common\models\forms\Flights\SearchForm::className()              => [
				'ru' => [
					'departureTown'      => 'Город отправления',
					'arrivalTown'        => 'Город назначения',
					'isRoundTrip'        => 'Обратно',
					'departureDate'      => 'Дата отправления',
					'departureDateShift' => '',
					'returnDate'         => 'Дата возвращения',
					'returnDateShift'    => '',
				],
				'en' => [
					'departureTown'      => 'Departure city',
					'arrivalTown'        => 'Destination city',
					'isRoundTrip'        => 'Round trip',
					'departureDate'      => 'Departure date',
					'departureDateShift' => '',
					'returnDate'         => 'Return date',
					'returnDateShift'    => '',
				]
			],
			//Форма поиска лучших тарфиов
			\common\models\forms\BestFares\SearchForm::className()            => [
				'ru' => [
					'departureTown' => 'Город отправления',
					'arrivalTown'   => 'Город назначения',
				],
				'en' => [
					'departureTown' => 'Departure city',
					'arrivalTown'   => 'Destination city',
				]
			],
			//Форма поиска туров
			\common\models\forms\Tours\SearchForm::className()                => [
				'ru' => [
					'townFrom' => 'Из города',
					'townTo'   => 'В город',
					'category' => 'Категория',
					'country'  => 'В страну',
					'region'   => 'Регион',
				],
				'en' => [
					'townFrom' => 'From city',
					'townTo'   => 'To city',
					'category' => 'Category',
					'country'  => 'To country',
					'region'   => 'To region',
				]
			],
			//Форма заказа билетов на регулярные рейсы
			\common\models\forms\RegularBooking\BookingInfo::className()      => [
				'ru' => [
					'townFrom'        => 'Город отправления',
					'townTo'          => 'Город назначения',
					'depDate'         => 'Вылет',
					'depDateShift'    => 'Сдвиг даты',
					'returnDate'      => 'Возвращение',
					'returnDateShift' => 'Сдвиг даты',
					'serviceClass'    => 'Класс обслуживания',
					'priority'        => 'Приоритеты',
					'usrPnr'          => 'Номер бронирования',
					'isRoundTrip'     => 'Обратно',
					'extraWishes'     => 'Другие пожелания'
				],
				'en' => [
					'townFrom'        => 'Departure city',
					'townTo'          => 'Target city',
					'depDate'         => 'Departure date',
					'depDateShift'    => 'Shift',
					'returnDate'      => 'Return date',
					'returnDateShift' => 'Shift',
					'serviceClass'    => 'Service class',
					'priority'        => 'Priority',
					'usrPnr'          => 'Booking number',
					'isRoundTrip'     => 'Round trip',
					'extraWishes'     => 'Another wishes'
				]
			],
			\common\models\forms\Common\ContactInfo::className()              => [
				'ru' => [
					'firstName'      => 'Имя',
					'middleName'     => 'Отчество',
					'lastName'       => 'Фамилия',
					'phone'          => 'Телефон',
					'email'          => 'Email',
					'city'           => 'Город',
					'contactToEmail' => 'Предпочтительна связь по email',
					'contactToPhone' => 'Предпочтительна связь по телефону',
				],
				'en' => [
					'firstName'      => 'Name',
					'middleName'     => 'Middle name',
					'lastName'       => 'Last Name',
					'phone'          => 'Phone',
					'email'          => 'Email',
					'city'           => 'City',
					'contactToEmail' => 'Contact with me by email',
					'contactToPhone' => 'Call me to phone',
				]
			],
			//Форма пассажиров для заказа билетов
			\common\models\forms\Common\Passenger::className()                => [
				'ru' => [
					'firstName'     => 'Имя',
					'middleName'    => 'Отчество',
					'lastName'      => 'Фамилия',
					'birthday'      => 'Дата рождения',
					'docType'       => 'Тип документа',
					'docSer'        => 'Серия',
					'docNum'        => 'Номер',
					'expireDocDate' => 'Срок действия',

				],
				'en' => [
					'firstName'     => 'Name',
					'middleName'    => 'Middle name',
					'lastName'      => 'Last Name',
					'birthday'      => 'Birthday',
					'docType'       => 'Document',
					'docSer'        => 'Serial',
					'docNum'        => 'Number',
					'expireDocDate' => 'Expire document date',
				]
			],
			\common\models\forms\CharterBooking\SearchBlocksForm::className() => [
				'ru' => [
					'isRoundTrip'   => 'Туда и обратно',
					'blockType'     => 'Маршрут',
					'adultCount'    => 'Взрослые',
					'childrenCount' => 'Дети',
					'fromDate'      => 'Начиная с даты',
				],
				'en' => [
					'isRoundTrip'   => 'Туда и обратно',
					'blockType'     => 'Маршрут',
					'adultCount'    => 'Взрослые',
					'childrenCount' => 'Дети',
					'fromDate'      => 'Начиная с даты',
				]
			]
			,
			\common\models\forms\TourBooking\LapsForm::className()            => [
				'ru' => [
					'lapDate' => 'Начало тура',
					'lapId'   => 'Выберите нужный вариант заезда'
				],
				'en' => [
					'lapDate' => 'Date',
					'lapId'   => 'Выберите нужный вариант заезда'
				]
			],
			CharterOrderForm::className()                                     => [
				'ru' => [
					'rulesAccepted' => 'Согласие',
				],
				'en' => [
					'rulesAccepted' => 'Согласие',
				]
			]
		];
	}
}