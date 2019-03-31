<?php

namespace common\components\widgets;

use common\components\TagDependency;
use common\models\scheme\sns\News;
use yii\base\Widget;

/**
 * @author isakov.v
 *
 * Виджет новостей
 */
class NewsWidget extends Widget {
	public $groupId;
	public $count = 5;

	public function init() {
		parent::init();
	}

	public function run() {
		$cacheKey = md5('DspNews::find().' . $this->groupId . 'limit=' . $this->count);
		$news = \Yii::$app->memcache->get($cacheKey);
		if (false === $news) {
			$news = News::find()
				->select('ID, NEWSDATE, TITLE')
				->where(['VISIBLE' => 1, 'NEWSBANDID' => $this->groupId])
				->orderBy('WHNCRT DESC')
				->limit($this->count)
				->all();
			\Yii::$app->memcache->set($cacheKey, $news, 0, new TagDependency([News::tableName()]));
		}

		return $this->render('news', ['news' => $news, 'groupId' => $this->groupId]);
	}
}