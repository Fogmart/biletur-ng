<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Компонент для получения картинок с сайта Билетур и их локального кеширования
 *
 *
 * @author isakov.v
 */
class RemoteImageCache extends Component {

	public function getImage($url, $size = '', $class = '') {
		$url = trim($url);

		//выпиливаем такой адрес из url
		$url = str_replace('https://www.airagency.ru', '', $url);

		//хак для уменшенных картинок, берем их оригиналы
		$url = str_replace('_100', '', $url);
		$url = str_replace('/100/', '/', $url);
		$url = str_replace('(2)', '', $url);
		$url = Yii::$app->params['imageSourceSite'] . $url;

		$hashName = md5($url) . '.' . strtolower(trim(pathinfo($url, PATHINFO_EXTENSION)));

		$cacheKey = 'imageCache' . $hashName;
		$imageCached = Yii::$app->cache->get($cacheKey);
		if (false === $imageCached) {
			if (!file_exists('images/cache/' . $hashName)) {
				$this->downloadFile($url, $hashName);
			}

			Yii::$app->cache->set($cacheKey, 1, 0);
		}

		//Если неободимое превью существует то отдадим его
		$ext = pathinfo($hashName, PATHINFO_EXTENSION);
		$prevName = str_replace('.' . $ext, '_' . $size . '.' . $ext, $hashName);
		if (file_exists('images/thumb/cache/' . $prevName)) {
			$url = Url::to(['images/thumb/cache/' . $prevName]);

			return Html::img($url, ['class' => $class]);
		}

		//Иначе создаем превью и отдаем

		$ext = pathinfo('/images/cache/' . $hashName, PATHINFO_EXTENSION);
		//Если запросили не файл а страницу, чтобы каждый раз не проверять во вьюшках, или вдруг с сервером что-то случилось
		//и мы получили вместо картинки страницу

		if (!file_exists('images/cache/' . $hashName) || $ext == 'ru') {
			return Yii::$app->imageCache->thumb('/images/image-not-found.png', $size, ['class' => $class]);
		}

		return Yii::$app->imageCache->thumb('/images/cache/' . $hashName, $size, ['class' => $class]);
	}

	/**
	 * Загрузка изображений со старого сатйа
	 *
	 * @param string $url
	 * @param string $hashName
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private function downloadFile($url, $hashName) {
		$remoteFilenameParts = explode('/', $url);
		foreach ($remoteFilenameParts as $part) {
			if (preg_match('/[а-я]+/msi', $part)) {
				$encodedPart = rawurlencode($part);
				$url = str_replace($part, $encodedPart, $url);
			}
		}
		if (!is_dir('images/cache')) {
			mkdir('images/cache');
		}
		@file_put_contents('images/cache/' . $hashName, @file_get_contents($url));
	}
}