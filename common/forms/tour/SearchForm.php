<?php

namespace common\forms\tour;

use common\components\tour\CommonTour;
use common\models\oracle\scheme\t3\RefItems;
use common\models\oracle\scheme\t3\RITourWps;
use yii\base\Model;
use yii\validators\NumberValidator;
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

	//Параметры пагинации
	const ITEMS_PER_PAGE = 20;

	public $page = 0;
	public $pageSize = self::ITEMS_PER_PAGE;
	public $pagination;

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
			[static::ATTR_SORT_BY, NumberValidator::class],
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
		$query = RefItems::find();
		$query->joinWith(RefItems::REL_ACTIVE);

		if (!empty($this->tourTo)) {
			$query->joinWith(RefItems::REL_WPS);
			if (false !== strpos($this->tourTo, 'country_')) {
				$query->andWhere(['LIKE', RITourWps::tableName() . '.' . RITourWps::ATTR_COUNTRY, str_replace('country_', '', $this->tourTo)]);
			}
			else {
				$query->andWhere([RITourWps::tableName() . '.' . RITourWps::ATTR_CITY_ID => $this->tourTo]);
			}

			$query->andWhere([RITourWps::tableName() . '.' . RITourWps::ATTR_DESTINATION_POINT => 1]);
		}

		$tours = $query->all();

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
		$paths = RITourWps::getActiveRegions();

		foreach ($paths as $country => $cities) {
			$result['country_' . $country] = $country;
			foreach ($cities as $id => $city) {
				$result[$id] = $city;
			}
		}


		//Dump::dDie($result);
		return $result;
	}
}