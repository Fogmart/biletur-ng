<?php

namespace common\components;

use common\models\oracle\scheme\sns\DspPhotos;
use Yii;
use yii\base\Component;

/**
 * Компонент для получения картинок с сайта Билетур и их локального кеширования
 *
 *
 * @author isakov.v
 */
class RemoteImageCache extends Component {
	public $imageSourceSite;

	/**
	 * @param string $url
	 * @param string $size
	 * @param string $class
	 * @param bool   $onlyPath
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getImage($url, $size = '', $class = '', $onlyPath = false) {
		$url = trim($url);

		//выпиливаем такой адрес из url
		$url = str_replace('https://www.airagency.ru', '', $url);

		//хак для уменшенных картинок, берем их оригиналы
		$url = str_replace('_100', '', $url);
		$url = str_replace('/100/', '/', $url);
		$url = str_replace('(2)', '', $url);
		$url = 'http://biletur.ru' . $url;

		$hashName = md5($url) . '.' . strtolower(trim(pathinfo($url, PATHINFO_EXTENSION)));

		//Чтобы каждый раз не дёргать диск на проверку скаченного файла поставим факт скачивания в кэш
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $hashName]);
		$imageCached = Yii::$app->cache->get($cacheKey);
		if (false === $imageCached) {
			if (!file_exists('images/uploads/cache/' . $hashName)) {
				static::_downloadFile($url, $hashName);
			}

			Yii::$app->cache->set($cacheKey, 1, null);
		}

		$ext = pathinfo('/images/uploads/cache/' . $hashName, PATHINFO_EXTENSION);

		//Если запросили не файл а страницу, чтобы каждый раз не проверять во вьюшках, или вдруг с сервером что-то случилось
		//и мы получили вместо картинки страницу
		if (!file_exists('images/uploads/cache/' . $hashName) || $ext == 'ru') {
			if ($onlyPath) {
				return Yii::$app->imageCache->thumbSrc('/images/uploads/image-not-found.png', $size);
			}
			return Yii::$app->imageCache->thumb('/images/uploads/image-not-found.png', $size, ['class' => $class]);
		}

		if($onlyPath) {
			return Yii::$app->imageCache->thumbSrc('/images/uploads/cache/' . $hashName, $size);
		}
		return Yii::$app->imageCache->thumb('/images/uploads/cache/' . $hashName, $size, ['class' => $class]);
	}

	/**
	 * Поиск фотографий по ключевому слову
	 *
	 * @param string $keyword
	 *
	 * @return DspPhotos[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getRandomImages($keyword) {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $keyword, 4]);
		$photos = Yii::$app->cache->get($cacheKey);
		if (false === $photos) {
			$photos = DspPhotos::find()
				->select([DspPhotos::ATTR_FILE_NAME])
				->andWhere(['LIKE', DspPhotos::ATTR_KEYWORDS, $keyword])
				->orderBy([DspPhotos::ATTR_WHNCRT => SORT_DESC])
				->limit(7)
				->indexBy(DspPhotos::ATTR_FILE_NAME)
				->all();

			$photos = array_keys($photos);

			shuffle($photos);

			Yii::$app->cache->set($cacheKey, $photos, 3600 * 24 * 7);
		}

		return $photos;
	}

	/**
	 * Загрузка изображений со старого сатйа
	 *
	 * @param string $url
	 * @param string $hashName
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	private static function _downloadFile($url, $hashName) {
		$remoteFilenameParts = explode('/', $url);
		foreach ($remoteFilenameParts as $part) {
			if (preg_match('/[а-я]+/msi', $part)) {
				$encodedPart = rawurlencode($part);
				$url = str_replace($part, $encodedPart, $url);
			}
		}
		if (!is_dir('images/uploads/cache')) {
			mkdir('images/uploads/cache');
		}

		@file_put_contents('images/uploads/cache/' . $hashName, @file_get_contents($url));
	}


}