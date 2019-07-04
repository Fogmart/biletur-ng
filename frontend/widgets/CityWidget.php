<?php

namespace frontend\widgets;

use common\base\helpers\Dump;
use common\models\Town;
use yii\base\Widget;
use Yii;
use yii\caching\TagDependency;

/**
 * Виджет выбора городов
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CityWidget extends Widget {

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function run() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, 2]);
		$townGrouped = Yii::$app->cache->get($cacheKey);
		if (false === $townGrouped) {
			/** @var Town[] $towns */
			$towns = Town::find()->activeTowns()->all();

			$townGrouped = [];
			foreach ($towns as $town) {
				$firstLetter = mb_substr($town->r_name,0, 1);
				$townGrouped[$firstLetter][] = $town;
			}

			Yii::$app->cache->set($cacheKey, $townGrouped, null, new TagDependency(['tags' => Town::class]));
		}

		return $this->render('city-widget', ['towns' => $townGrouped]);
	}
}