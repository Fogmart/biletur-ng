<?php

namespace backend\assets;

use yii\helpers\ArrayHelper;
use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/site-admin.css',
	];

	public $js = [
	];

	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];

	/**
	 * Переопределение метода для публикации ресурсов с хэшем версии
	 *
	 * @param \yii\web\View $view
	 */
	public function registerAssetFiles($view) {
		$manifest = self::getManifest();

		$manager = $view->getAssetManager();
		foreach ($this->js as $js) {
			if (is_array($js)) {
				$file = array_shift($js);
				$options = ArrayHelper::merge($this->jsOptions, $js);
				$view->registerJsFile('/internal' . $manager->getAssetUrl($this, $manifest[$file]), $options);
			}
			else {
				if ($js !== null) {
					$view->registerJsFile('/internal' . $manager->getAssetUrl($this, $manifest[$js]), $this->jsOptions);
				}
			}
		}

		foreach ($this->css as $css) {
			if (is_array($css)) {
				$file = array_shift($css);
				$options = ArrayHelper::merge($this->cssOptions, $css);
				$view->registerCssFile('/internal' . $manager->getAssetUrl($this, $manifest['/' . $file]), $options);
			}
			else {
				if ($css !== null) {
					$view->registerCssFile('/internal' . $manager->getAssetUrl($this, $manifest['/' . $css]), $this->cssOptions);
				}
			}
		}
	}

	/**
	 * Получение хэшированного ключа для публикации ресурса
	 *
	 * @return false|mixed|string
	 */
	public static function getManifest() {
		$path = \Yii::getAlias("@webroot") . "/mix-manifest.json";
		$manifest = file_get_contents($path);
		$manifest = json_decode($manifest, true);

		return $manifest;
	}
}
