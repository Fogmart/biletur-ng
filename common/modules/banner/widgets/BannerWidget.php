<?php

use yii\base\Widget;
use common\modules\banner\models\Banner;
use yii\db\Expression;

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
		/** @var Banner $banner */
		$banner = Banner::find()
			->andWhere([Banner::ATTR_ZONE => $this->zone])
			->andWhere(['>=', Banner::ATTR_BEG_DATE, new Expression('sysdate')])
			->andWhere(['<=', Banner::ATTR_END_DATE, new Expression('sysdate')])
			->one();

		$banner->show_count++;
		$banner->save();

		return $this->render('banner', ['banner' => $banner]);
	}
}