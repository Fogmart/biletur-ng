<?php

namespace common\components\widgets;

use common\components\TagDependency;
use common\models\scheme\sns\Towns;
use yii\base\Widget;

/**
 * @author isakov.v
 *
 *
 */
class CityWidget extends Widget {
	public function init() {
		parent::init();
	}

	public function run() {
		$cacheKey = 'CitiesWidget::getTown()';
		$cities = \Yii::$app->memcache->get($cacheKey);
		if (false === $cities) {
			$cities = Towns::find()->getActiveTowns()->all();
			\Yii::$app->memcache->set(
				$cacheKey, $cities, 0, new TagDependency([Towns::className()])
			);
		}

		return $this->render('cities', ['cities' => $cities]);
	}
}