<?php

namespace common\forms\etm;

use common\base\helpers\Dump;
use common\modules\api\etm\components\EtmApi;
use common\modules\api\etm\query\Direction;
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

	/** @var int Кол-во младенцев */
	public $infantCount = 0;
	const ATTR_INFANT_COUNT = 'infantCount';

	/** @var bool Прямой рейс */
	public $isDirect = 0;
	const ATTR_IS_DIRECT = 'isDirect';

	/** @var string Класс обслуживания */
	public $class = 'E';
	const ATTR_CLASS = 'class';

	/** @var bool Искать на фиксированную дату */
	public $isFixedDate = 0;
	const ATTR_IS_FIXED_DATE = 'isFixedDate';

	/** @var int Максимальная цена */
	public $maxPrice;
	const ATTR_MAX_PRICE = 'maxPrice';

	/** @var array Классы обслуживания */
	const CLASSES = [
		'E' => 'E',
		'B' => 'B'
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
			static::ATTR_INFANT_COUNT  => 'Кол-во младенцев',
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
		$direction = new Direction();

		$direction->departure_code = $this->airportFrom;
		$direction->arrival_code = $this->airportTo;
		$direction->date = $this->date;
		$query->directions[] = $direction;
		$query->child_qnt = (int)$this->childCount;
		$query->adult_qnt = (int)$this->adultCount;
		$query->infant_qnt = (int)$this->infantCount;
		$query->direct = (bool)$this->isDirect;
		$query->class = $this->class;
		$query->flexible = (bool)$this->isFixedDate;

		//Если заполнена дата возврата то меняем местами направления и даты, и добавляем еще одно направление
		if (!empty($this->backDate)) {
			$direction = new Direction();
			$direction->departure_code = $this->airportTo;
			$direction->arrival_code = $this->airportFrom;
			$direction->date = $this->backDate;
			$query->directions[] = $direction;
		}

		$query = static::_prepareQuery($query);

		/** @var \common\modules\api\etm\response\SearchFlightsResponse $response */
		$this->result = Yii::$app->etmApi->sendRequest(EtmApi::METHOD_SEARCH, $query, true);

		Dump::dDie($this->result);
	}

	/**
	 * Очистка обьекта запроса от незаполненных полей
	 *
	 * @param SearchFlights $query
	 *
	 * @return \common\modules\api\etm\query\SearchFlights
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private static function _prepareQuery(SearchFlights $query): SearchFlights {
		foreach ($query as $property => $value) {
			if (null === $value) {
				unset($query->$property);
			}
		}

		return $query;
	}
}