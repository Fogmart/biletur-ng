<?php

namespace visa\assets;

use yii\helpers\ArrayHelper;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class AppAsset extends AssetBundle {
	public $basePath = '@webroot';

	public $baseUrl = '@web';

	public $css = [
		//'css/visa.css',
	];

	public $js = [
		//'/js/visa-biletur.js',
	];

	public $depends = [
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
				$view->registerJsFile($manager->getAssetUrl($this, $manifest[$file]), $options);
			}
			else {
				if ($js !== null) {
					$view->registerJsFile($manager->getAssetUrl($this, $manifest[$js]), $this->jsOptions);
				}
			}
		}
		foreach ($this->css as $css) {
			if (is_array($css)) {
				$file = array_shift($css);
				$options = ArrayHelper::merge($this->cssOptions, $css);
				$view->registerCssFile($manager->getAssetUrl($this, $manifest['/' . $file]), $options);
			}
			else {
				if ($css !== null) {
					$view->registerCssFile($manager->getAssetUrl($this, $manifest['/' . $css]), $this->cssOptions);
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
		$manifest = file_get_contents(\Yii::getAlias("@webroot") . "/mix-manifest.json");
		$manifest = json_decode($manifest, true);

		return $manifest;
	}
}
