<?php

namespace common\components;

use common\base\helpers\StringHelper;
use common\modules\seo\models\Seo;
use Yii;
use yii\caching\TagDependency;
use yii\web\Controller;

/**
 * @author isakov.v
 * Контроллер, поддерживающий мультиязычность,
 * модифицирует путь до вьюшки в зависимости от языка окружения
 *
 */
class FrontendController extends Controller {
	public function init() {
		parent::init();
	}

	public function beforeAction($action) {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__]);
		/** @var \common\modules\seo\models\Seo $seo */
		$seo = Yii::$app->cache->get($cacheKey);
		if (false === $seo) {
			/** @var \common\modules\seo\models\Seo $seo */
			$seo = Seo::find()->where([
				Seo::ATTR_URL => 'https://biletur-ng.loc/avia/',
			])->one();

			Yii::$app->cache->set($cacheKey, $seo, null, new TagDependency(['tags' => Seo::class]));
		}

		$description = '';
		$title = '';
		$keywords = '';

		if (null !== $seo) {
			$description = $seo->seo_description;
			$title = $seo->seo_title;
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

		return parent::beforeAction($action);
	}


	/**
	 * Получение ссылки на указанное действие исходя из контроллера.
	 *
	 * @param string $actionName   Название действия
	 * @param array  $actionParams Дополнительные параметры
	 *
	 *
	 * @return string
	 * @author Isakov Vladislav
	 *
	 */
	public static function getActionUrl($actionName, array $actionParams = []) {
		$prefix = null;

		$controllerName = preg_replace('/Controller$/', '', StringHelper::basename(static::className()));
		$controllerName = mb_strtolower(preg_replace('~(?!\b)([A-Z])~', '-\\1', $controllerName)); // Преобразуем название контроллера к формату url (aaa-bbb-ccc-..)

		$actionParams[0] = implode('/', [
			$controllerName,
			$actionName,
		]);
		$actionParams[0] = '/' . $prefix . $actionParams[0];

		$url = Yii::$app->urlManager->createUrl($actionParams);

		return $url;
	}
}