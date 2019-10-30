<?php

namespace common\forms\etm;

use common\modules\api\etm\components\EtmApi;
use common\modules\api\etm\query\Directions;
use common\modules\api\etm\query\SearchFlights;
use Yii;
use yii\base\Model;
use yii\validators\DateValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;

/**
 * Форма поиска авиабилетов
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SearchForm extends Model {

	/** @var string Аэропорт отправления */
	public $airportFrom;
	const ATTR_AIRPORT_FROM = 'airportFrom';

	/** @var string Аэропорт назначения */
	public $airportTo;
	const ATTR_AIRPORT_TO = 'airportTo';

	/** @var string Дата туда */
	public $date;
	const ATTR_DATE = 'date';

	/** @var string Дата обратно */
	public $backDate;
	const ATTR_BACK_DATE = 'backDate';

	/** @var int Кол-во взрослых */
	public $adultCount = 1;
	const ATTR_ADULT_COUNT = 'adultCount';

	/** @var int Кол-во детей */
	public $childCount = 0;
	const ATTR_CHILD_COUNT = 'childCount';

	/** @var int Кол-во инфантов */
	public $infantCount = 0;
	const ATTR_INFANT_COUNT = 'infantCount';

	/** @var bool Прямой рейс */
	public $isDirect = false;
	const ATTR_IS_DIRECT = 'isDirect';

	/** @var string Класс обслуживания */
	public $class;
	const ATTR_CLASS = 'class';

	/** @var bool Искать на фиксированную дату */
	public $isFixedDate = false;
	const ATTR_IS_FIXED_DATE = 'isFixedDate';

	/** @var int */
	public $maxPrice;
	const ATTR_MAX_PRICE = 'maxPrice';

	/** @var array Классы обслуживания */
	const CLASSES = [
		'E',
		'B'
	];

	/** @var \common\modules\api\etm\response\SearchFlightsResponse */
	public $result;

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_AIRPORT_FROM, RequiredValidator::class],
			[static::ATTR_AIRPORT_TO, RequiredValidator::class],
			[static::ATTR_DATE, RequiredValidator::class],
			[static::ATTR_CLASS, RequiredValidator::class],
			[static::ATTR_ADULT_COUNT, RequiredValidator::class],
			[static::ATTR_CHILD_COUNT, RequiredValidator::class],
			[static::ATTR_INFANT_COUNT, RequiredValidator::class],

			[static::ATTR_ADULT_COUNT, NumberValidator::class],
			[static::ATTR_CHILD_COUNT, NumberValidator::class],
			[static::ATTR_INFANT_COUNT, NumberValidator::class],

			[static::ATTR_IS_DIRECT, SafeValidator::class],
			[static::ATTR_IS_FIXED_DATE, SafeValidator::class],
			[static::ATTR_DATE, DateValidator::class, 'format' => 'Y-m-d'],
			[static::ATTR_BACK_DATE, DateValidator::class, 'format' => 'Y-m-d'],
		];
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_AIRPORT_FROM  => 'Аэропорт вылета',
			static::ATTR_AIRPORT_TO    => 'Аэропорт прилета',
			static::ATTR_DATE          => 'Дата',
			static::ATTR_ADULT_COUNT   => 'Кол-во взрослых',
			static::ATTR_CHILD_COUNT   => 'Кол-во детей',
			static::ATTR_IS_DIRECT     => 'Прямой рейс',
			static::ATTR_CLASS         => 'Класс обслуживания',
			static::ATTR_IS_FIXED_DATE => 'Фиксированная дата',
		];
	}

	/**
	 * Поиск в ETM
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function search() {
		$query = new SearchFlights();

		$query->directions = new Directions();
		$query->directions->departure_code = $this->airportFrom;
		$query->directions->arrival_code = $this->airportTo;
		$query->directions->date = $this->date;
		$query->child_qnt = $this->childCount;
		$query->adult_qnt = $this->adultCount;
		$query->direct = $this->isDirect;
		$query->class = $this->class;
		$query->flexible = $this->isFixedDate;

		/** @var \common\modules\api\etm\response\SearchFlightsResponse $response */
		$this->result['from'] = Yii::$app->etmApi->sendRequest(EtmApi::METHOD_SEARCH, $query, true);
		$this->result['back'] = null;

		//Если заполнена дата возврата то меняем местами направления и даты, и делаем еще один запрос
		if (!empty($this->backDate)) {
			$query->directions = new Directions();
			$query->directions->departure_code = $this->airportTo;
			$query->directions->arrival_code = $this->airportFrom;
			$query->directions->date = $this->backDate;

			/** @var \common\modules\api\etm\response\SearchFlightsResponse $response */
			$this->result['back'] = Yii::$app->etmApi->sendRequest(EtmApi::METHOD_SEARCH, $query, true);
		}


	}
}