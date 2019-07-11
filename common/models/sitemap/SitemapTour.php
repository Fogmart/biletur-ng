<?php

namespace common\models\sitemap;

use common\base\helpers\StringHelper;
use common\components\behaviors\SitemapBehavior;
use common\components\tour\CommonTour;
use common\models\oracle\scheme\t3\RefItems;
use frontend\controllers\TourController;

/**
 * Переопределение туров для выгрузки в Sitemap
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SitemapTour extends RefItems {
	public function behaviors() {
		return [
			'sitemap' => [
				'class'       => SitemapBehavior::class,
				'scope'       => function ($model) {
					/** @var RefItems|\yii\db\ActiveQuery $model */
					$model->select([RefItems::ATTR_ID, RefItems::ATTR_NAME, RefItems::ATTR_WHNUPD]);
					$model->andWhere([RefItems::ATTR_ACTIVE => 1]);
				},
				'dataClosure' => function ($model) {
					/** @var self $model */
					return [
						'loc'        => TourController::getActionUrl(TourController::ACTION_VIEW, ['id' => $model->ID, 'src' => CommonTour::SOURCE_BILETUR, 'slug' => StringHelper::urlAlias(trim(strip_tags($model->NAME)))]),
						'lastmod'    => strtotime($model->WHNUPD),
						'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
						'priority'   => 0.8
					];
				}
			],
		];
	}
}