<?php

namespace common\models;

use common\components\SiteModel;
use common\interfaces\ILinkedModels;
use common\models\oracle\scheme\sns\DspCountries;
use yii\db\Expression;

/**
 * Модель Стран
 *
 * @author isakov.v
 *
 * Поля таблицы:
 * @property int    $id
 * @property int    $old_id            <= $ID
 * @property string $name
 * @property string $r_name
 * @property string $e_name
 * @property string $code
 * @property double $aura_id
 * @property double $shwinguide
 * @property string $yandex_weather_id
 * @property string $flag_image
 * @property string $insert_stamp      <= $WHNCRT
 * @property string $update_stamp      <= $WHNCHNG
 */
class Country extends SiteModel implements ILinkedModels {

	const ATTR_ID = 'id';
	const ATTR_OLD_ID = 'old_id';
	const ATTR_NAME = 'name';
	const ATTR_R_NAME = 'r_name';
	const ATTR_E_NAME = 'e_name';
	const ATTR_CODE = 'code';
	const ATTR_AURA_ID = 'aura_id';
	const ATTR_SHWINGUIDE = 'shwinguide';
	const ATTR_YANDEX_WEATHER_ID = 'yandex_weather_id';
	const ATTR_FLAG_IMAGE = 'flag_image';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	public static function tableName() {
		return '{{%country}}';
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
			static::class => DspCountries::class
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
		return DspCountries::ATTR_WHNUPD;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedFields() {
		return [
			static::ATTR_OLD_ID            => DspCountries::ATTR_ID,
			static::ATTR_NAME              => DspCountries::ATTR_NAME,
			static::ATTR_R_NAME            => DspCountries::ATTR_RNAME,
			static::ATTR_E_NAME            => DspCountries::ATTR_ENAME,
			static::ATTR_CODE              => DspCountries::ATTR_CODE,
			static::ATTR_AURA_ID           => DspCountries::ATTR_ID_AURA,
			static::ATTR_SHWINGUIDE        => DspCountries::ATTR_SHWINGUIDE,
			static::ATTR_YANDEX_WEATHER_ID => DspCountries::ATTR_YNDXWTHRID,
			static::ATTR_FLAG_IMAGE        => DspCountries::ATTR_FLAGNAME,
			static::ATTR_INSERT_STAMP      => DspCountries::ATTR_WHNCRT,
			static::ATTR_UPDATE_STAMP      => DspCountries::ATTR_WHNUPD,
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
			case DspCountries::ATTR_ENAME:
			case DspCountries::ATTR_RNAME:
			case DspCountries::ATTR_CODE:
				if (empty($data)) {
					return ' ';
				}

				return trim($data);
				break;
			case DspCountries::ATTR_WHNCRT:
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
	 * Пусть до изображения флага страны
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getFlagImage() {
		return '/images/flags/48' . DIRECTORY_SEPARATOR . strtoupper($this->code) . '.png';
	}
}