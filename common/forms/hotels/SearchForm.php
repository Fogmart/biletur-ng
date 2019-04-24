<?php

namespace common\forms\hotels;

use common\base\helpers\DateHelper;
use common\modules\api\ostrovok\components\OstrovokApi;
use common\modules\api\ostrovok\exceptions\OstrovokResponseException;
use sem\helpers\ArrayHelper;
use Yii;
use yii\base\Model;
use yii\validators\EachValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
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
	public $adultCount = 2;
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
			[static::ATTR_TITLE, RequiredValidator::class],
			[static::ATTR_CHECK_IN, RequiredValidator::class],
			[static::ATTR_CHECK_OUT, RequiredValidator::class],

			[static::ATTR_TITLE, StringValidator::class],
			[static::ATTR_CHECK_IN, StringValidator::class],
			[static::ATTR_CHECK_OUT, StringValidator::class],
			[static::ATTR_ADULT_COUNT, NumberValidator::class],
			[static::ATTR_CHILD_COUNT, NumberValidator::class],
			[static::ATTR_CHILD_AGES, EachValidator::class, 'rule' => 'integer'],
		];
	}

	/**
	 *
	 * @return \common\modules\api\ostrovok\components\objects\OstrovokResponse
	 *
	 * @throws \common\modules\api\ostrovok\exceptions\OstrovokResponseException
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function search() {
		$this->result = $this->searchOstrovok();

		return $this->result;
	}

	/**
	 * Поиск отелей по Апи Островка
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function searchOstrovok() {
		$titleTypeParams = explode('|', $this->title);
		$this->objectType = $titleTypeParams[1];

		if ($this->objectType === static::OBJECT_TYPE_REGION) {
			$id = explode('|', $this->title)[0];
			$param['region_id'] = $id;
		}
		else {
			$id = explode('|', $this->title)[0];
			$param['ids'] = [$id];
		}

		$param['checkin'] = date(DateHelper::DATE_FORMAT_OSTROVOK, strtotime($this->checkIn));
		$param['checkout'] = date(DateHelper::DATE_FORMAT_OSTROVOK, strtotime($this->checkOut));

		if ($this->adultCount <> 2) {
			$param['adults'] = $this->adultCount;
		}

		if ($this->childCount > 0) {
			$childrenAges = [];
			for ($i = 0; $i < $this->childCount; $i++) {
				$childrenAges[] = rand(0, 17); //пока заполняем возрасты детей рандомными значениями
			}
			$param['children'] = $childrenAges;
		}

		$param['currency'] = 'RUB';

		$api = Yii::$app->ostrovokApi;
		$api->method = OstrovokApi::METHOD_HOTEL_RATES;
		$api->params = $param;

		/** @var \common\modules\api\ostrovok\components\objects\OstrovokResponse $result */
		$result = $api->sendRequest();

		if (null !== $result->error) {
			throw new OstrovokResponseException('Ошибка поиска вариантов размещения: ' . $result->error->description . PHP_EOL . $result->error->extra);
		}

		$rates = [];

		foreach ($result->result['rates'] as $rate) { /** @var \common\modules\api\ostrovok\components\objects\Rate $rate */

		}

		return $result;
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
		}
		else {
			$result = ArrayHelper::map($result['results'], 'id', 'text');
		}

		return $result;
	}
}