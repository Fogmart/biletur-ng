<?php

namespace common\components;

use Yii;
use yii\base\Component;

/**
 * Получение курса для валюты
 *
 * @package common\components
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CurrencySync extends Component {
	/**
	 * @param $currency
	 *
	 * @return false|mixed|string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getCurRate($currency) {
		$url = 'https://www.cbr-xml-daily.ru/daily_json.js';

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__]);
		$results = Yii::$app->cache->get($cacheKey);

		if (false === $results) {
			$results = file_get_contents($url);
			$results = json_decode($results);

			Yii::$app->cache->set($cacheKey, $results, 3600 * 12);
		}

		if (property_exists($results->Valute, $currency)) {
			return $results->Valute->$currency->Value;
		}

		return 1;
	}
}