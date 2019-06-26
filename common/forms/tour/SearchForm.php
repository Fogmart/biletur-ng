<?php

namespace common\forms\tour;

use Adldap\Configuration\Validators\IntegerValidator;
use common\components\tour\CommonTour;
use yii\base\Model;
use yii\validators\StringValidator;

class SearchForm extends Model {

	/** @var string Страна/город(по справочнику Билетур) */
	public $tourTo;
	const ATTR_TOUR_TO = 'tourTo';

	/** @var string Тип тура(по справочнику Билетур) */
	public $tourType;
	const ATTR_TOUR_TYPE = 'tourType';

	/** @var string Из какого города(по справочнику Билетур) */
	public $fromCity;
	const ATTR_FROM_CITY = 'fromCity';

	/** @var int */
	public $sortBy;
	const ATTR_SORT_BY = 'sortBy';

	/** @var CommonTour[] */
	public $result;

	/** @var int Сортировка от дешевых */
	const SORT_TYPE_PRICE_MIN = 0;

	/** @var int Сортировка от дорогих */
	const SORT_TYPE_PRICE_MAX = 1;

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_FROM_CITY => 'Из города',
			static::ATTR_TOUR_TO   => 'Страна, курорт, город',
			static::ATTR_TOUR_TYPE => 'Вид отдыха'
		];
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_FROM_CITY, StringValidator::class],
			[static::ATTR_TOUR_TO, StringValidator::class],
			[static::ATTR_TOUR_TYPE, StringValidator::class],
			[static::ATTR_SORT_BY, IntegerValidator::class],
		];
	}

	/**
	 * @return \common\components\tour\CommonTour[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function search() {
		$this->result = $this->_searchBiletur();

		return $this->result;
	}

	/**
	 * @return CommonTour[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _searchBiletur() {
		$tours = [];

		return $tours;
	}

	/**
	 * Возможные направления туров
	 *
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getTourToPaths() {
		$result = [];

		return $result;
	}
}