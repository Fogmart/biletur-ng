<?php

namespace common\components;

use common\models\oracle\scheme\sns\DspPhotos;
use sem\helpers\Html;
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
	 * @param bool   $needThumb
	 * @param bool   $isBiletur
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getImage($url, $size = '', $class = '', $onlyPath = false, $needThumb = true, $isBiletur = true) {
		if ($isBiletur) {
			$url = trim($url);

			//выпиливаем такой адрес из url
			$url = str_replace('https://www.airagency.ru', '', $url);

			//хак для уменшенных картинок, берем их оригиналы
			$url = str_replace('_100', '', $url);
			$url = str_replace('/100/', '/', $url);
			$url = str_replace('(2)', '', $url);
			$url = 'http://biletur.ru' . $url;
		}

		$hashName = md5($url) . '.' . strtolower(trim(pathinfo($url, PATHINFO_EXTENSION)));
		$subDir = substr($hashName, 0, 2);
		$imageFolder = Yii::getAlias('@remoteImageCache') . DIRECTORY_SEPARATOR . $subDir;

		//Чтобы каждый раз не дёргать диск на проверку скаченного файла поставим факт скачивания в кэш
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $hashName, 6]);
		$imageCached = Yii::$app->cache->get($cacheKey);

		if (false === $imageCached) {
			if (!file_exists($imageFolder . DIRECTORY_SEPARATOR . $hashName)) {
				static::_downloadFile($url, $hashName);
			}

			Yii::$app->cache->set($cacheKey, 1, null);
		}

		if ($needThumb) {
			$ext = pathinfo($imageFolder . DIRECTORY_SEPARATOR . $hashName, PATHINFO_EXTENSION);
			//Если запросили не файл а страницу, чтобы каждый раз не проверять во вьюшках, или вдруг с сервером что-то случилось
			//и мы получили вместо картинки страницу
			if (!file_exists($imageFolder . DIRECTORY_SEPARATOR . $hashName) || $ext == 'ru') {

				if ($onlyPath) {
					return Yii::$app->imageresize->getUrl(Yii::getAlias('@images') . DIRECTORY_SEPARATOR . 'image-not-found.png', $size, $size);
				}
				echo $imageFolder . DIRECTORY_SEPARATOR . $hashName;

				return Html::img(Yii::$app->imageresize->getUrl(Yii::getAlias('@images') . DIRECTORY_SEPARATOR . 'image-not-found.png', $size, $size), ['class' => $class]);
			}

			if ($onlyPath) {
				return Yii::$app->imageresize->getUrl($imageFolder . DIRECTORY_SEPARATOR . $hashName, $size, $size);
			}

			return Html::img(Yii::$app->imageresize->getUrl($imageFolder . DIRECTORY_SEPARATOR . $hashName, $size, $size), ['class' => $class]);
		}

		return '/images/cache/' . $hashName;
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

		$imageFolder = Yii::getAlias('@remoteImageCache') . DIRECTORY_SEPARATOR . substr($hashName, 0, 2);

		if (!is_dir($imageFolder)) {
			mkdir($imageFolder);
		}

		$userAgents = [
			'Mozilla/5.0 (Linux; Android 7.0; BLL-L22 Build/HUAWEIBLL-L22) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
			'Mozilla/5.0 (Linux; Android 5.1.1; A37fw Build/LMY47V) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.84 Mobile Safari/537.36',
			'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0',
			'Mozilla/5.0 (Linux; Android 4.4.2; de-de; SAMSUNG GT-I9195 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/1.5 Chrome/28.0.1500.94 Mobile Safari/537.36',
			'Mozilla/5.0 (Linux; U; Android 2.2.1; en-us; Nexus One Build/FRG83) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
			'Mozilla/5.0 (Linux; Android 6.0.1; SM-G532M Build/MMB29T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Mobile Safari/537.36',
			'Mozilla/5.0 (Linux; Android 5.1; A37f Build/LMY47V) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.93 Mobile Safari/537.36',
			'Mozilla/5.0 (Linux; Android 6.0; vivo 1713 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.124 Mobile Safari/537.36',
			'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 YaBrowser/17.3.1.840 Yowser/2.5 Safari/537.36',
			'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 YaBrowser/17.3.1.840 Yowser/2.5 Safari/537.36',
			'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.95 YaBrowser/17.1.0.2034 Yowser/2.5 Safari/537.36',
			'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 YaBrowser/17.9.1.768 Yowser/2.5 Safari/537.36',
			'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 YaBrowser/18.3.1.1232 Yowser/2.5 Safari/537.36',
			'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
			'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
			'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.157 Safari/537.36',
			'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
			'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
			'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
			'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36',
			'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36'
		];

		$index = rand(0, count($userAgents) - 1);

		$command = 'wget -U "' . $userAgents[$index] . '" -c -T 60 -O ' . $imageFolder . DIRECTORY_SEPARATOR . $hashName . ' ' . $url;
		exec($command, $output, $status);

		if ($output === []) {
			file_put_contents($imageFolder . DIRECTORY_SEPARATOR . $hashName, file_get_contents($url));
		}
	}
}