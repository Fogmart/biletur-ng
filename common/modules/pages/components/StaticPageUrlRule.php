<?php
namespace common\modules\pages\components;

use common\base\helpers\Dump;
use common\modules\pages\models\Page;
use yii\base\BaseObject;
use yii\base\Component;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\UrlRule;
use yii\web\UrlRuleInterface;
use Yii;

class StaticPageUrlRule extends UrlRule implements UrlRuleInterface {

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function parseRequest($manager, $request) {
		$pathInfo = $request->pathInfo;
		$usingSuffix = false;

		if ('/' !== substr($pathInfo, -1)) {
			$pathInfo .= '/';
			$usingSuffix = true;
		}

		$pathsMap = $this->_getPathsMap();

		Dump::dDie([$pathInfo, $pathsMap]);

		$id = array_search($pathInfo, $pathsMap);

		if (false === $id) {
			return false;
		}

		if (true === $usingSuffix) {
			Yii::$app->response->redirect($pathInfo, 301);
			Yii::$app->end();
		}

		$_GET['id'] = $id;

		return ['page/static-page/index', []];
	}

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function createUrl($manager, $route, $params) {

		$pathsMap = $this->_getPathsMap();
		if ($route === 'static-page/index' && isset($params['id'], $pathsMap[$params['id']])) {
			return $pathsMap[$params['id']] . '/';
		}

		return false;
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function _getPathsMap() {
		$cacheKey = Yii::$app->cache->buildKey(['static-rules']);
		$pathsMap = Yii::$app->cache->get($cacheKey);
		if (false === $pathsMap) {
			$pathsMap = Page::find()->where([Page::ATTR_IS_PUBLISHED => true])->all();
			$pathsMap = ArrayHelper::map($pathsMap, 'id', 'slug');

			Yii::$app->cache->set($cacheKey, $pathsMap, null, new TagDependency(['tags' => Page::class]));
		}

		return $pathsMap;
	}

}