<?php

namespace common\forms\hotels;

use common\base\helpers\Dump;
use sem\helpers\ArrayHelper;
use Yii;
use yii\base\Model;
use yii\validators\EachValidator;
use yii\validators\NumberValidator;
use yii\validators\StringValidator;

/**
 * Общая форма поиска отелей
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SearchForm extends Model {

	/** @var string */
	public $title;
	const ATTR_TITLE = 'title';

	/** @var string */
	public $checkIn;
	const ATTR_CHECK_IN = 'checkIn';

	/** @var string */
	public $checkOut;
	const ATTR_CHECK_OUT = 'checkOut';

	/** @var int */
	public $adultCount = 1;
	const ATTR_ADULT_COUNT = 'adultCount';

	/** @var int int */
	public $childCount = 0;
	const ATTR_CHILD_COUNT = 'childCount';

	/** @var array */
	public $childAges;
	const ATTR_CHILD_AGES = 'childAges';

	public $objectType;
	const ATTR_OBJECT_TYPE = 'objectType';

	/** @var string */
	public $source = self::API_SOURCE_OSTROVOK;
	const ATTR_SOURCE = 'source';

	public $result;

	const API_SOURCE_OSTROVOK = 0;

	const OBJECT_TYPE_HOTEL = 0;
	const OBJECT_TYPE_REGION = 1;

	const OBJECT_TYPES = [
		self::OBJECT_TYPE_HOTEL  => 'hotel',
		self::OBJECT_TYPE_REGION => 'region',
	];

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_TITLE => 'Отель, регион',
		];
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_TITLE, StringValidator::class],
			[static::ATTR_CHECK_IN, StringValidator::class],
			[static::ATTR_CHECK_OUT, StringValidator::class],
			[static::ATTR_ADULT_COUNT, NumberValidator::class],
			[static::ATTR_CHILD_COUNT, NumberValidator::class],
			[static::ATTR_CHILD_AGES, EachValidator::class, 'rule' => 'integer'],
		];
	}

	/**
	 * Поиск отелей
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function search() {
		$titleTypeParams = explode(',', $this->title);
		$title = $titleTypeParams[0];
		$this->objectType = $titleTypeParams[1];

		$this->result = $this;
	}

	/**
	 * Возвращает данные последнего автокомплита для их восстановления после отправки формы
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getLastAutocompleteOstrovok() {
		$cacheKey = Yii::$app->cache->buildKey(['lastAutocompleteOstrovok', Yii::$app->session->id]);
		$result = Yii::$app->cache->get($cacheKey);


		if (false === $result) {
			$result = [];
		} else {
			$result = ArrayHelper::map($result['results'], 'id', 'text');

		}



		return $result;
	}
}