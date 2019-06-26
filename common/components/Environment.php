<?php

namespace common\components;

use common\models\Town;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\caching\TagDependency;

/**
 * Компонент для получения данных о текущем окружении.
 *
 * @author isakov.v
 */
class Environment extends Component {

	/** @var string $language Язык сайта по-умолчанию */
	public $defaultLanguage;
	/** @var string $defaultAirportId */
	public $defaultAirportCode;
	/** @var string Идентификатор города по-умолчанию (если город определить не удалось). */
	public $defaultCityId;
	/** @var string Идентификатор города по-умолчанию (если город определить не удалось). */
	public $defaultArrCityId;
	/** @var string Идентификатор зоны туров по-умолчанию */
	public $defaultTourZone;
	/** @var Town Переменная города для которого отображается сайт. */
	private $_city;
	/** @var string $_language Язык сайта */
	private $_language;
	/** @var  Airport Аэропорт по-умолчанию для табло/расписания */
	private $_airport;

	/**
	 * @return int
	 */
	public function getTourZone() {
		return $this->defaultTourZone;
	}

	/**
	 * @return Airport
	 */
	public function getAirport() {

		if (null === $this->_airport) {
			$this->_airport = $this->_getAirportByCity();
		}

		if (null === $this->_airport || $this->_airport->IATACODE === null) {
			$cacheKey = 'Environment::getAirport(' . $this->defaultAirportCode . ')';
			$this->_airport = Yii::$app->cache->get($cacheKey);
			if (false === $this->_airport) {
				$this->_airport = Airport::findOne(['IATACODE' => $this->defaultAirportCode]);
				Yii::$app->cache->set($cacheKey, $this->_airport, 0, new TagDependency([Airport::className()]));
			}
		}

		return $this->_airport;
	}

	/**
	 * Получение аэропорта по городу окружения
	 * @return Airport
	 */
	private function _getAirportByCity() {
		if (null === $this->_city) {
			$this->_city = $this->getCity();
		}

		$cacheKey = 'Environment::getAirportByCity(' . $this->_city->CODE . ')';
		$this->_airport = Yii::$app->cache->get($cacheKey);
		if (false === $this->_airport) {
			$this->_airport = Airport::findOne(['TOWNCODE' => $this->_city->CODE]);
			Yii::$app->cache->set($cacheKey, $this->_airport, 0, new TagDependency([Airport::className()]));
		}

		return $this->_airport;
	}

	/**
	 * Получение города.
	 *
	 * @author isakov.v
	 *
	 * @return \common\models\scheme\sns\Town
	 * @throws Exception
	 */
	public function getCity() {
		if (null === $this->_city) {
			// Пытаемся достать город из url (например: moscow.biletur.ru)
			$this->_getCityByUrl();
			if (null !== $this->_city) {
				return $this->_city;
			}

			// Пытаемся достать указанный город из куки
			$this->getCityByCookie();
			if (null !== $this->_city) {
				return $this->_city;
			}

			// Пытаемся определить город по GEOip
			$this->getCityByGEOIp();
			if (null !== $this->_city) {
				return $this->_city;
			}

			// Если не получили город ни одним из методов - присваиваем город по умолчанию.
			if (null === $this->_city && isset($this->defaultCityId)) {
				$cacheKey = 'Environment::getCity(' . $this->defaultCityId . ')';
				$this->_city = Yii::$app->cache->get($cacheKey);

				if (false === $this->_city) {
					$this->_city = Town::find()->with(['arrCity'])->where(['ID' => $this->defaultCityId])->one();
					if (null === $this->_city) {
						throw new Exception('Ошибка: Невозможно определить город, и присвоить город по умолчанию.');
					}
					else {
						Yii::$app->cache->set(
							$cacheKey, $this->_city, 24 * 60 * 60, new TagDependency([Town::className()])
						);
					}
				}
			}
			else {
				throw new Exception('Ошибка: Невозможно определить город, а дефолтный город не задан.');
			}
		}

		if (null !== $this->_city) {
			// Записываем id города в куку, для быстрого обнаружения
			Yii::$app->response->cookies->add(
				new \yii\web\Cookie(['name' => 'current_path', 'value' => $this->_city->ID])
			);
		}

		return $this->_city;
	}

	/**
	 * Получение города из кук.
	 *
	 * @author isakov.v
	 *
	 * @return Cities
	 */
	private function getCityByCookie() {
		if (isset(Yii::$app->request->cookies['current_path'])) {
			$cityId = Yii::$app->request->cookies['current_path'];
			if (null !== $cityId) {
				$cacheKey = 'Environment::getCity(' . $cityId . ')';
				$cacheCity = Yii::$app->cache->get($cacheKey);
				if (false === $cacheCity) {
					$this->_city = Town::find()->with(['arrCity'])->where(['ID' => $cityId->value])->one();
					if (null !== $this->_city) {
						Yii::$app->cache->set(
							$cacheKey, $this->_city, 24 * 60 * 60, new TagDependency([Town::class])
						);
					}
				}
				else {
					$this->_city = $cacheCity;
				}
			}
		}

		return $this->_city;
	}

	/**
	 * Получение города по geoip.
	 *
	 * @author isakov.v
	 * @return Cities
	 */
	private function getCityByGEOIp() {
		$ip = Yii::$app->request->userIP;
		if (null !== $ip) {
			$ip = ip2long($ip);

			//Модификация отрицательных значений ip-адреса
			if ($ip < 0) {
				$ip += 4294967296;
			}

			$cacheKey = 'Environment::getCity(' . $ip . ')';
			$cacheCity = Yii::$app->cache->get($cacheKey);
			if (false === $cacheCity) {
				/** @var IpRange $geoCity */
				$geoCity = IpRange::find()
					->where(['>=', 'BEGRANGE', $ip])
					->andWhere(['<=', 'ENDRANGE', $ip])
					->one();

				if (null !== $geoCity) {
					$this->_city = Town::find()->with(['arrCity'])->where(['ID' => $geoCity->CITYID])->one();
					if (null !== $this->_city) {
						Yii::$app->cache->set(
							$cacheKey, $this->_city, 24 * 60 * 60, new TagDependency([Town::class])
						);
					}
				}
			}
			else {
				$this->_city = $cacheCity;
			}
		}
		if (null !== $this->_city) {
			// Записываем id города в куку, для быстрого обнаружения
			$this->setCityById($this->_city->ID);
		}

		return $this->_city;
	}

	/**
	 * Принудительная установка города в куки
	 *
	 * @param $cityId
	 */
	public function setCityById($cityId) {
		$this->_city = Town::find()->with(['arrCity'])->where(['ID' => $cityId])->one();
		if (null !== $this->_city) {
			// Записываем id города в куку, для быстрого обнаружения
			Yii::$app->response->cookies->add(
				new \yii\web\Cookie(['name' => 'current_path', 'value' => $this->_city->ID])
			);
		}
		else {
			Yii::$app->response->cookies->add(
				new \yii\web\Cookie(['name' => 'current_path', 'value' => $this->defaultCityId])
			);
		}
	}

	/**
	 * Получения языка приложения
	 * @return string
	 */
	public function getLanguage() {

		if (null === $this->_language) {
			$this->_getLanguageByCookie();
		}

		if (null === $this->_language) {
			$this->_getLanguageByDomain();
		}

		if (null === $this->_language) {
			$this->_language = $this->defaultLanguage;
		}

		Yii::$app->language = $this->_language;
		Yii::$app->sourceLanguage = $this->_language;

		return $this->_language;
	}

	/**
	 * Установка языка
	 *
	 * @param $language
	 */
	public function setLanguage($language) {
		$this->_language = $language;
		$this->_setLanguageToCookie();
	}

	private function _setLanguageToCookie() {
		Yii::$app->response->cookies->add(
			new \yii\web\Cookie(['name' => 'language', 'value' => $this->_language])
		);
	}

	/**
	 * Получение языка из куки
	 */
	private function _getLanguageByCookie() {
		if (isset(Yii::$app->request->cookies['language'])) {
			$this->_language = Yii::$app->request->cookies['language'];
		}
	}

	/**
	 * Получение языка по домену
	 */
	private function _getLanguageByDomain() {
		$this->_language = null;
	}

	/**
	 * Получение города из url.
	 *
	 * @author isakov.v
	 * @return Cities
	 */
	private function _getCityByUrl() {
		$subDomain = null;
		if (!defined('STDIN')
			&& preg_match(
				'/^([\S]+)\.([^\.]+)\.([^\.]+)$/', $_SERVER['HTTP_HOST'], $_common_matches
			)
		) {
			$subDomain = $_common_matches[1];
		}

		if (null !== $subDomain) {
			$cacheKey = 'Environment::getCity(' . $subDomain . ')';
			$cacheCity = Yii::$app->cache->get($cacheKey);

			if (false === $cacheCity) {
				$this->_city = Town::findOne(['ename' => $subDomain]);
				if (null !== $this->_city) {
					Yii::$app->cache->set(
						$cacheKey, $this->_city, 24 * 60 * 60, new TagDependency([Town::class])
					);
				}
			}
			else {
				$this->_city = $cacheCity;
			}
		}

		if (null !== $this->_city) {
			// Записываем id города в куку, для быстрого обнаружения
			Yii::$app->response->cookies->add(
				new \yii\web\Cookie(['name' => 'current_path', 'value' => $this->_city->ID])
			);
		}

		// todo Редирект на основной домен
		return $this->_city;
	}
}