<?php

namespace frontend\controllers;

use common\components\FrontendController;
use common\modules\pages\models\Page;
use common\modules\seo\models\Seo;
use Yii;
use yii\caching\TagDependency;

/**
 * Контроллер статических страниц
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class StaticPageController extends FrontendController {

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
		$page = Yii::$app->cache->get($cacheKey);
		if (false === $page) {
			/** @var Page $page */
			$page = Page::find()->where([Page::ATTR_ID => $id, Page::ATTR_IS_PUBLISHED => true])->one();

			Yii::$app->cache->set($cacheKey, $page, null, new TagDependency(['tags' => Page::class]));
		}

		$url = Yii::$app->request->url;
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, 'seo', $id]);
		$seo = Yii::$app->cache->get($cacheKey);
		if (false === $seo) {
			/** @var \common\modules\seo\models\Seo $seo */
			$seo = Seo::find()->where([
				'LIKE',
				Seo::ATTR_URL, $url ,
			])->one();

			Yii::$app->cache->set($cacheKey, $seo, null, new TagDependency(['tags' => Seo::class]));
		}

		$title = $page->seo_title;
		$description = $page->seo_description;
		$keywords = $page->seo_keywords;

		//Если есть настройки из модуля SEO то считаем и приоритетными
		if (null !== $seo) {
			$title = $seo->seo_title;
			$description = $seo->seo_description;
			$keywords = $seo->seo_keywords;
		}

		$this->view->title = $title . ' - ' . Yii::$app->name;

		$this->view->registerMetaTag([
				'name'    => 'title',
				'content' => $title
			]
		);

		$this->view->registerMetaTag([
				'name'    => 'keywords',
				'content' => $keywords
			]
		);

		$this->view->registerMetaTag([
				'name'    => 'description',
				'content' => $description
			]
		);

		return $this->render('static', ['html' => $page->html]);
	}
}