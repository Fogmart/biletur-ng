<?php

namespace common\forms\etm;

use common\modules\api\etm\components\EtmApi;
use common\modules\api\etm\query\Directions;
use common\modules\api\etm\query\SearchFlights;
use yii\base\Model;
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

	/** @var string Дата */
	public $date;
	const ATTR_DATE = 'date';

	/** @var int Кол-во взрослых */
	public $adultCount;
	const ATTR_ADULT_COUNT = 'adultCount';

	/** @var int Кол-во детей */
	public $childCount;
	const ATTR_CHILD_COUNT = 'childCount';

	/** @var bool Прямой рейс */
	public $isDirect = false;
	const ATTR_IS_DIRECT = 'isDirect';

	/** @var string Класс обслуживания */
	public $class;
	const ATTR_CLASS = 'class';

	/** @var bool Искать на фиксированную дату */
	public $isFixedDate = false;
	const ATTR_IS_FIXED_DATE = 'isFixedDate';

	/** @var \common\modules\api\etm\response\SearchFlightsResponse */
	public $result;

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_AIRPORT_FROM, SafeValidator::class],
			[static::ATTR_AIRPORT_TO, SafeValidator::class],
			[static::ATTR_DATE, SafeValidator::class],
			[static::ATTR_ADULT_COUNT, SafeValidator::class],
			[static::ATTR_CHILD_COUNT, SafeValidator::class],
			[static::ATTR_IS_DIRECT, SafeValidator::class],
			[static::ATTR_CLASS, SafeValidator::class],
			[static::ATTR_IS_FIXED_DATE, SafeValidator::class],
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

		$api = new EtmApi();

		/** @var \common\modules\api\etm\response\SearchFlightsResponse $response */
		$this->result = $api->sendRequest(EtmApi::METHOD_SEARCH, $query, true);
	}

}