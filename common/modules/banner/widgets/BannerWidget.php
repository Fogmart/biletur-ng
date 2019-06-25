<?php

use yii\base\Widget;
use common\modules\banner\models\Banner;
use yii\db\Expression;
use yii\caching\TagDependency;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class BannerWidget extends Widget {
	public $zone;
	const ATTR_ZONE = 'zone';

	/**
	 * Отрисовка баннера
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function run() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $this->zone]);

		/** @var Banner $banner */
		$banner = Yii::$app->cache->get($cacheKey);
		if (false === $banner) {
			$banner = Banner::find()
				->andWhere([Banner::ATTR_ZONE => $this->zone])
				->andWhere(['>=', Banner::ATTR_BEG_DATE, new Expression('sysdate')])
				->andWhere(['<=', Banner::ATTR_END_DATE, new Expression('sysdate')])
				->one();

			$banner->show_count++;
			$banner->save();

			Yii::$app->cache->set($cacheKey, $banner, null, new TagDependency(['tags' => [Banner::class]]));
		}
		return $this->render('banner', ['banner' => $banner]);
	}
}