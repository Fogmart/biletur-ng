<?php

namespace frontend\controllers;

use common\modules\pages\models\Page;
use yii\caching\TagDependency;
use yii\web\Controller;
use Yii;

/**
 * Контроллер статических страниц
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class StaticPageController extends Controller {

	/**
	 * Отображение статической страницы
	 *
	 * @param int $id
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex($id) {

		$this->layout = 'common';

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $id]);
		$page= Yii::$app->cache->get($cacheKey);
		if (false === $page) {
			/** @var Page $page */
			$page = Page::find()->where([Page::ATTR_ID => $id, Page::ATTR_IS_PUBLISHED => true])->one();

			Yii::$app->cache->set($cacheKey, $page, null, new TagDependency(['tags' => Page::class]));
		}

		$this->view->title =  $page->seo_title . ' - ' . Yii::$app->name;

		$this->view->registerMetaTag([
				'name'      => 'title',
				'content' => $page->seo_title
			]
		);

		$this->view->registerMetaTag([
				'name'      => 'keywords',
				'content' => $page->seo_keywords
			]
		);

		$this->view->registerMetaTag([
				'name'      => 'description',
				'content' => $page->seo_description
			]
		);

		return $this->render('static', ['html' => $page->html]);
	}
}