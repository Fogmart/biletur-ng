<?php

namespace common\models;

use common\components\SiteModel;
use common\interfaces\ILinkedModels;
use common\models\oracle\scheme\sns\DspTowns;
use common\models\queries\QueryTown;
use common\models\scheme\sns\queries\QueryTowns;
use Yii;
use yii\caching\TagDependency;
use yii\db\Expression;

/**
 * Модель Городов
 *
 * @author isakov.v
 *
 * Поля таблицы:
 * @property int    $id
 * @property int    $old_id                 <= $ID
 * @property string $name                   <= $NAME
 * @property string $r_name                 <= $RNAME
 * @property string $e_name                 <= $ENAME
 * @property string $code                   <= $CODE
 * @property int    $country_id             <= $STATEID
 * @property string $country_code
 * @property double $adm_reg_id
 * @property string $adm_code
 * @property string $iata_code
 * @property string $ikao_code
 * @property string $phone_code
 * @property string $latitude
 * @property string $longitude
 * @property double $aura_id
 * @property double $shwinguide
 * @property string $yandex_weather_id
 * @property double $gmt_shift
 * @property double $rang
 * @property string $insert_stamp           <= $WHNCRT
 * @property string $update_stamp           <= $WHNUPD
 * @property int    $id_geobase
 *
 * @property-read \common\models\Country $country
 */
class Town extends SiteModel implements ILinkedModels {

	const ATTR_ID = 'id';
	const ATTR_OLD_ID = 'old_id';
	const ATTR_NAME = 'name';
	const ATTR_R_NAME = 'r_name';
	const ATTR_E_NAME = 'e_name';
	const ATTR_CODE = 'code';
	const ATTR_COUNTRY_ID = 'country_id';
	const ATTR_COUNTRY_CODE = 'country_code';
	const ATTR_ADM_REG_ID = 'adm_reg_id';
	const ATTR_ADM_CODE = 'adm_code';
	const ATTR_IATA_CODE = 'iata_code';
	const ATTR_IKAO_CODE = 'ikao_code';
	const ATTR_PHONE_CODE = 'phone_code';
	const ATTR_LATITUDE = 'latitude';
	const ATTR_LONGITUDE = 'longitude';
	const ATTR_AURA_ID = 'aura_id';
	const ATTR_SHWINGUIDE = 'shwinguide';
	const ATTR_YANDEX_WEATHER_ID = 'yandex_weather_id';
	const ATTR_GMT_SHIFT = 'gmt_shift';
	const ATTR_RANG = 'rang';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';
	const ATTR_ID_GEOBASE = 'id_geobase';


	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function tableName() {
		return '{{%town}}';
	}

	public static function find() {
		return new QueryTown(get_called_class());
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getOldIdField() {
		return static::ATTR_OLD_ID;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedModel() {
		return [
			static::class => DspTowns::class
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getInternalInvalidateField() {
		return static::ATTR_UPDATE_STAMP;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getOuterInvalidateField() {
		return DspTowns::ATTR_WHNUPD;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedFields() {
		return [
			static::ATTR_OLD_ID            => DspTowns::ATTR_ID,
			static::ATTR_R_NAME            => DspTowns::ATTR_RNAME,
			static::ATTR_E_NAME            => DspTowns::ATTR_ENAME,
			static::ATTR_CODE              => DspTowns::ATTR_CODE,
			static::ATTR_COUNTRY_ID        => DspTowns::ATTR_STATEID,
			static::ATTR_COUNTRY_CODE      => DspTowns::ATTR_STATECODE,
			static::ATTR_ADM_REG_ID        => DspTowns::ATTR_ADMREGID,
			static::ATTR_ADM_CODE          => DspTowns::ATTR_ADMCODE,
			static::ATTR_IATA_CODE         => DspTowns::ATTR_IATACODE,
			static::ATTR_IKAO_CODE         => DspTowns::ATTR_IKAOCODE,
			static::ATTR_PHONE_CODE        => DspTowns::ATTR_PHONECODE,
			static::ATTR_LATITUDE          => DspTowns::ATTR_LATITUDE,
			static::ATTR_LONGITUDE         => DspTowns::ATTR_LONGITUDE,
			static::ATTR_AURA_ID           => DspTowns::ATTR_AURA_ID,
			static::ATTR_SHWINGUIDE        => DspTowns::ATTR_SHWINGUIDE,
			static::ATTR_YANDEX_WEATHER_ID => DspTowns::ATTR_YNDXWTHRID,
			static::ATTR_RANG              => DspTowns::ATTR_RANG,
			static::ATTR_NAME              => DspTowns::ATTR_NAME,
			static::ATTR_INSERT_STAMP      => DspTowns::ATTR_WHNCRT,
			static::ATTR_UPDATE_STAMP      => DspTowns::ATTR_WHNUPD,
		];
	}

	/**
	 * Конвертация данных
	 *
	 * @param string $fieldName
	 * @param mixed  $data
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getConvertedField($fieldName, $data) {
		switch ($fieldName) {
			case DspTowns::ATTR_STATEID:
				/** @var \common\models\Country $country */
				$country = Country::find()->where([Country::ATTR_OLD_ID => $data])->one();
				if (null !== $country) {
					return $country->id;
				}
				break;
			case DspTowns::ATTR_WHNCRT:
				if (null === $data) {
					return new Expression('sysdate');
				}

				return $data;
				break;
			default:
				return trim($data);
				break;
		}
	}

	/**
	 * @param int|null $id
	 *
	 * @return string|null
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getNameByOldId($id) {

		if (null === $id) {
			return null;
		}

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $id]);
		$city = Yii::$app->cache->get($cacheKey);
		if (false === $city) {
			$city = static::findOne([static::ATTR_OLD_ID => $id]);

			Yii::$app->cache->set($cacheKey, $city, null, new TagDependency(['tags' => [static::class]]));
		}

		if (null !== $city) {
			return $city->r_name;
		}

		return null;
	}

	/**
	 * @param string|null $name
	 *
	 * @return string|null
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getOldIdByName($name) {

		if (null === $name) {
			return null;
		}

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $name, 1]);
		$city = Yii::$app->cache->get($cacheKey);
		if (false === $city) {
			$city = static::findOne([static::ATTR_R_NAME => $name]);

			Yii::$app->cache->set($cacheKey, $city, null, new TagDependency(['tags' => [static::class]]));
		}

		if (null !== $city) {
			return $city->old_id;
		}

		return null;
	}

	/**
	 * @param string|null $name
	 *
	 * @return string|null
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getCountryNameByName($name) {

		if (null === $name) {
			return null;
		}

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $name]);
		$city = Yii::$app->cache->get($cacheKey);
		if (false === $city) {
			$city = static::findOne([static::ATTR_NAME => $name]);

			Yii::$app->cache->set($cacheKey, $city, null, new TagDependency(['tags' => [static::class]]));
		}

		if (null !== $city) {
			return $city->country->r_name;
		}

		return null;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getCountry() {
		return $this->hasOne(Country::class, [Country::ATTR_ID => static::ATTR_COUNTRY_ID]);
	}

	const REL_COUNTRY = 'country';
}